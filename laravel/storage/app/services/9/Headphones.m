%Headphones - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: support of SimpleHeadphoneIR (16.04.2025)
% #Author: Michael Mihocic: create csv files with properties (09.07.2025)
% #Author: Michael Mihocic: bug fixed when plotting more than 6 curves per side (implementing mod code to repeat colors); y-limit range fixed for high amplitudes (18.06.2026)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function Headphones(SOFAfile)

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;
addpath('../shared'); % add the path to shared functions
logfile="Headphones.log";
fid = fopen(logfile, "w");
s = pwd;
disp(["pwd = " s]);

%% Prologue: (un)comment here if you want to:
close all; % clean-up first
tic; % timer
SOFAstart; % remove this optionally
warning('off'); %jw:note disable all warnings

%jw:note Check if function called with parameter. If not, use command line parameter^M
if(exist("SOFAfile"))
  if(length(SOFAfile)==0)
    disp('The SOFA file name SOFAfile is empty');
  end
else
  % Use command line parameter for SOFAfile
  disp(argv);
  arg_list = argv();
  fn = arg_list{1};
  disp(fn);
  SOFAfile = fn;
end
%disp(["SOFAfile = " SOFAfile]);

%% Load SOFA file
Obj=SOFAload(SOFAfile);

SaveSOFAproperties(Obj, SOFAfile);
if isoctave; fputs(fid, ["Successfully saved SOFA details to csv files\n"]); end

if isoctave; fputs(fid, [ "About to plot\n"]); end

%% Plot a few figures
switch Obj.GLOBAL_SOFAConventions
  % maybe other directivity cases will follow
  case 'SimpleHeadphoneIR';
    if isoctave; fputs(fid, [ "case Headphones\n"]); end
    figure('Name',SOFAfile);
    if isoctave; fputs(fid, [ "just done figure\n"]); end

    hold on; box on;
    cols='bgrmky';

    %if ~isoctave
    if ~isoctave
      if isfield(Obj, 'MeasurementDate')
        meastime=[0; diff(Obj.MeasurementDate)]; % diff not working in Octave
      else
        meastime=diff(Obj.GLOBAL_DateCreated); % diff not working in Octave
      end
    end

    for ii=1:Obj.API.M
      % plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,1,:)),Obj.Data.SamplingRate))));
      plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,1,:)),Obj.Data.SamplingRate))),cols(mod(ii, length(cols))+1) );
      % plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,1,:)),Obj.Data.SamplingRate))),cols(ii));
      leg{ii}=['Left #' num2str(ii)];
    end

    for ii=1:Obj.API.M
      % plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,2,:)),Obj.Data.SamplingRate)))-20);
      plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,2,:)),Obj.Data.SamplingRate)))-20,cols(mod(ii, length(cols))+1) );
      % plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,2,:)),Obj.Data.SamplingRate)))-20,cols(ii));
      % if ~isoctave
      %   leg{ii+Obj.API.M}=['Right #' num2str(ii) ':' num2str(meastime(ii)) ' seconds later'];
      % else
      leg{ii+Obj.API.M}=['Right #' num2str(ii)];
      % end
    end

    xlim([-200 18200]);
    yMax = max(15,ceil(max(20*log10(abs(fft(squeeze(Obj.Data.IR(1,1,:)),Obj.Data.SamplingRate))))));
    axis([-200 18200 -65 yMax]);
    % leg{1}='#1, first measurement';
    legend(leg,'Location','best');
    title('Amplitude Spectra of Headphones Measurements: Left, Right [-20 dB]')
    xlabel('Frequency (Hz)')
    ylabel('Amplitude (dB)')

    if isoctave; fputs(fid, [ "just done some figure adaptations\n"]); end

    % set(gcf, 'Name', 'SOFAfile')
    % if isoctave; fputs(fid, [ "renamed figure\n"]); end
    print ('-dpng', "-r600", [SOFAfile '_1.png'])
    %print ("-r600", '/tmp/hrtf_1.png');
    if isoctave; fputs(fid, [ "just printed " SOFAfile "_1.png\n"]); end
end % switch


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer

end

