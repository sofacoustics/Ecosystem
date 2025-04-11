<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrcidVerificationController extends Controller
{
	
    /**
     * Handles the ORCID verification process.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
				$orcidFromForm = $request->input('orcid_id');
				
        if (!$orcidFromForm) {
            return back()->withErrors(['orcid_id' => 'ORCID ID is required.']);
        }

        // Configuration for the ORCID API (Sandbox or Production)
        $orcidApiUrl = config('services.orcid.api_url'); // e.g., 'https://pub.orcid.org/v3.0' for production
        $orcidClientId = config('services.orcid.client_id');
        $orcidClientSecret = config('services.orcid.client_secret');
        $orcidRedirectUri = route('orcid.callback'); // Define this route in your web.php
        $orcidScope = '/authenticate'; // Adjust scopes as needed

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
        //$orcidId = $request->input('orcid');
				
        $error = $request->input('error');

        if ($error) {
            return redirect()->route('orcid.verification.failed')->withErrors(['orcid' => 'ORCID authorization failed: ' . $error]);
        }

        //if (!$code || !$orcidId) {
				if (!$code ) {
            return redirect()->route('orcid.verification.failed')->withErrors(['orcid' => 'Invalid ORCID authorization response.']);
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
                'redirect_uri' => route('orcid.callback'),
            ]);

            $response->throw(); // Throw an exception for HTTP errors

            $accessTokenData = $response->json();
            $accessToken = $accessTokenData['access_token'] ?? null;
            $orcidFromToken = $accessTokenData['orcid'] ?? null;

						//dd([$accessToken, $orcidFromToken, $accessTokenData]);

            if ($accessToken && $orcidFromToken) {
                // ORCID ID successfully verified!
                // You can now associate this ORCID ID with the user in your database.
                // You might want to store the access token for future API calls (with user's consent).

                // Example: Assuming you have a User model
                auth()->user()->update(['orcid_verified_at' => now()]);

                return redirect()->route('orcid.verification.success')->with('success', 'ORCID ID verified successfully!');
            } else {
                return redirect()->route('orcid.verification.failed')->withErrors(['orcid' => 'ORCID verification failed: Could not retrieve or match ORCID ID.']);
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            return redirect()->route('orcid.verification.failed')->withErrors(['orcid' => 'Error communicating with ORCID: ' . $e->getMessage()]);
        }
    }

    /**
     * Displays a success message after ORCID verification.
     *
     * @return \Illuminate\View\View
     */
    public function verificationSuccess()
    {
        return view('orcid.success');
    }

    /**
     * Displays a failure message after ORCID verification.
     *
     * @return \Illuminate\View\View
     */
    public function verificationFailed()
    {
        return view('orcid.failed');
    }
}