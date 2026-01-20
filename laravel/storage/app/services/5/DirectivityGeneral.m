%DirectivityGeneral - Plots geometry and polar directivity plots for norm frequencies

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: support of Directivity SOFA files implemented (15.04.2025)
% #Author: Michael Mihocic: conventions restriction removed (03.06.2025)
% #Author: Michael Mihocic: file renamed; attempting to create new figures, still work in progress... (27.06.2025)
% #Author: Michael Mihocic: figure creation finished, Octave also supported; SOFA properties stored to csv files (09.07.2025)
% #Author: Michael Mihocic: directivity creation based on R as parameter; first M is plotted; some bugs fixed (15.07.2025)
% #Author: Piotr Majdak: added path to shared functions, moved the call to SOFA Properties to shared (27.12.2025)
% #Author: Piotr Majdak: title "no valid data" if all data are NaNs, skips frequency if too far away (20.1.2025)
% #Author: Piotr Majdak: cleaned up, mySOFA... functions removed (20.1.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.


function DirectivityGeneral(SOFAfile)

	addpath('../shared'); % add the path to shared functions
	isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

	logfile="DirectivityGeneral.log";
	fid = fopen(logfile, "w");

	tic; % timer
	SOFAstart; % remove this optionally
	warning('off'); % disable all warnings

  	% Check if function called with parameter. If not, use command line parameter
	if(exist("SOFAfile"))
    if(length(SOFAfile)==0)
        disp('The SOFA file name SOFAfile is empty');
    end
	else
			% Use command line parameter for SOFAfile
    arg_list = argv();
    fn = arg_list{1};
    SOFAfile = fn;
	end

	Obj=SOFAload(SOFAfile); 	% Load SOFA file

	SaveSOFAproperties(Obj, SOFAfile);
	fputs(fid, ["Successfully saved SOFA details to csv files\n"]);
	fputs(fid, [ "About to plot\n"]);

 	%% PLOT GEOMETRY
	SOFAplotGeometry(Obj);
	fputs(fid, [ "just done SOFAplotGeometry\n"]);
	view(45,30);
	axis equal
	fputs(fid, [ "adapted view\n"]);
	title([num2str(Obj.API.R) ' Positions']);
	print ('-dpng', "-r600", [SOFAfile '_geometry.png']);
	fputs(fid, [ "just printed " SOFAfile "_geometry.png\n"]);
  disp(['Saved figure: ' SOFAfile '_geometry.png']);

	%% POLAR PLOTS

	% Use TF data if available
	% receiver = 1;  % Left ear
	% all receivers but only first source, first measurement, first emitter
	% Dimension = MRN

	freqs = [31.5, 63, 125, 250, 500, 1000, 2000, 4000, 8000, 16000];  % Frequencies to plot (Hz)

	% Check if TF data exists
	if isfield(Obj.Data, 'Real') && isfield(Obj.Data, 'Imag')

    % get MxN dimension; squeeze had the problem when M=1
    % TF = double(squeeze(Obj.Data.Real(:, receiver, :) + 1i * Obj.Data.Imag(:, receiver, :)));
    C = Obj.Data.Real(1, :, :) + 1i * Obj.Data.Imag(1, :, :);
    TF = reshape(C, size(Obj.Data.Real,2), size(Obj.Data.Real,3));

    freq = double(Obj.N);  % Frequency axis from file
    pos = Obj.ReceiverPosition(:,:);

    mask = abs(pos(:,2)) <= 10; % get indices of azi 0 +/-10 deg
    pos_filtered = pos(mask, :);
    TF_filtered = TF(mask, :);

    azi = mod(pos_filtered(:,1), 360);  % Azimuth in degrees (wrapped to 0–360)
    theta = deg2rad(azi);      % Convert to radians for polarplot
    [theta_sorted, idx] = sort(theta);

    for f = freqs
      [~, idxF] = min(abs(freq - f));
      if freq(idxF)>1.5*f || freq(idxF)<=0.5*f, continue; end % skip if the found frequency in `freq` would fall into other `freqs`

      mag = 20 * log10(abs(TF_filtered(:, idxF)));
      figure;
      if prod(isnan(mag(idx)-min(mag)))>0
          % all entries are NaN, replace by zero and adapt the title
        polar(theta_sorted, zeros(size(mag(idx)-min(mag))));
        preTitle = 'No valida data found';
      else
        polar(theta_sorted, (mag(idx)-min(mag)));
        preTitle = 'Magnitudes (in dB)';
      endif

      fTitle = freq(idxF);
      if mod(fTitle,1) == 0
        digitTitle = '0';
      else
        digitTitle = '1';
      end
      if fTitle == f
        title(sprintf([preTitle ' at %.' digitTitle 'f Hz'], fTitle));
      else
        title(sprintf([preTitle ' at nominal %.0f Hz, actual %.' digitTitle 'f Hz'], f, fTitle));
      end

      set(gcf, 'Name', sprintf('HRTF_%d', round(f)));
      fputs(fid, [ "renamed figure\n"]);
        % Save figure as PNG
      filename = sprintf('%s_amphorizontal_%d', SOFAfile, round(f));
      print ('-dpng', "-r600", [filename '.png'])
      disp(['Saved figure: ' filename]);
      fputs(fid, [ "just printed " filename ".png\n"]);
    end
	else
			error('No valid data.');
	end

	%% Epilogue
	disp('DONE');
	fputs(fid, [ "DONE\n"]);
	fclose(fid);
	toc; % timer
end



