<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Select which action to process
     */
		public function process(ProfileUpdateRequest $request): RedirectResponse
    {
			$action = $request->input('action');
			
			if ($action === 'update') { return $this->update($request); }
			elseif ($action === 'orcidUnlink') { return $this->orcidUnlink($request); }
			elseif ($action === 'orcidLink') { return $this->orcidLink($request); }
			else dd($action);
		}
		
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
			
			$request->user()->fill($request->validated());

			if ($request->user()->isDirty('email')) 
			{
				$request->user()->email_verified_at = null;
			}
			
			$request->user()->save();

			return Redirect::route('profile.edit')->with('status', 'Profile updated...');
    }

    /**
     * Unlink ORCID with the User
     */
    public function orcidUnlink(ProfileUpdateRequest $request): RedirectResponse
    {
			$request->user()->fill($request->validated());

			if ($request->user()->isDirty('email')) 
			{
				$request->user()->email_verified_at = null;
			}
			
			$request->user()->orcid_verified_at = null;
			$request->user()->save();

			return Redirect::route('profile.edit')->with('status', 'ORCID unlinked...');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
		
    /**
     * Handles the ORCID verification process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function orcidLink(Request $request)
    {
				$orcidFromForm = $request->input('orcid');
				
        // Configuration for the ORCID API 
        $orcidApiUrl = config('services.orcid.api_url'); 
        $orcidClientId = config('services.orcid.client_id');
        $orcidClientSecret = config('services.orcid.client_secret');
        $orcidRedirectUri = route('profile.callback'); 
        $orcidScope = '/authenticate'; 

        // Construct the authorization URL
        $authorizationUrl = $orcidApiUrl . '/oauth/authorize?' . http_build_query([
            'client_id' => $orcidClientId,
            'response_type' => 'code',
            'scope' => $orcidScope,
            'redirect_uri' => $orcidRedirectUri,
            'orcid' => $orcidFromForm, // Optionally pre-fill the ORCID ID
        ]);

        // Redirect the user to the ORCID authorization page
        return redirect()->away($authorizationUrl);
		}

    /**
     * Handles the callback from ORCID after authorization.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');
        $error = $request->input('error');

        if ($error) {
            return redirect()->route('profile.edit')->withErrors(['orcid' => 'ORCID authorization failed: ' . $error]);
        }

				if (!$code ) {
            return redirect()->route('profile.edit')->withErrors(['orcid' => 'Invalid ORCID authorization response.']);
        }

        // Configuration for the ORCID API
        $orcidApiUrl = config('services.orcid.api_url');
        $orcidClientId = config('services.orcid.client_id');
        $orcidClientSecret = config('services.orcid.client_secret');
        $orcidTokenUrl = $orcidApiUrl . '/oauth/token';

        try {
            // Exchange the authorization code for an access token
            $response = Http::asForm()->post($orcidTokenUrl, [
                'client_id' => $orcidClientId,
                'client_secret' => $orcidClientSecret,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => route('profile.callback'),
            ]);

            $response->throw(); // Throw an exception for HTTP errors

            $accessTokenData = $response->json();
            $accessToken = $accessTokenData['access_token'] ?? null;
            $orcidFromToken = $accessTokenData['orcid'] ?? null;
						$nameFromToken = $accessTokenData['name'] ?? null;

            if ($accessToken && $orcidFromToken) {
                // ORCID ID successfully verified! Update the fields
								auth()->user()->update(['orcid' => $orcidFromToken]);
								auth()->user()->update(['name' => $nameFromToken]);
                auth()->user()->update(['orcid_verified_at' => now()]);
                return redirect()->route('profile.edit')->with('status', 'ORCID Link successful...');;

            } else {
                return redirect()->route('profile.edit')->withErrors(['orcid' => 'ORCID verification failed: Could not retrieve or match ORCID ID.']);
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            return redirect()->route('profile.edit')->withErrors(['orcid' => 'Error communicating with ORCID: ' . $e->getMessage()]);
        }
    }


}
