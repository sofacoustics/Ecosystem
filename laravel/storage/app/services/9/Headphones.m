%Headphones - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: support of SimpleHeadphoneIR (16.04.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function Headphones(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="/home/sonicom/isf-sonicom-laravel/laravel/storage/app/tools/1/Headphones.log"
fid = fopen(logfile, "w");
s = pwd;
disp(["pwd = " s]);

%% Prologue: (un)comment here if you want to:
% clc; % clean-up first
close all; % clean-up first
tic; % timer
SOFAstart; % remove this optionally
% warning('off','SOFA:upgrade');
% warning('off','SOFA:load');
% warning('off','SOFA:save');
% warning('off','SOFA:save:API');
warning('off'); %jw:note disable all warnings

%jw:note Check if function called with parameter. If not, use command line parameter^M
if(exist("SOFAfile"))
	if(length(SOFAfile)==0)
		disp('The SOFA file name SOFAfile is empty');
	end
else
	% Use command line parameter for SOFAfile
	% disp("SOFAfile does not exist");
	disp(argv);
	arg_list = argv();
	fn = arg_list{1};
	disp(fn);
	SOFAfile = fn;
end
%disp(["SOFAfile = " SOFAfile]);

%% Load SOFA file
%disp(['Loading: ' SOFAfile]);
Obj=SOFAload(SOFAfile);

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
  plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,1,:)),Obj.Data.SamplingRate))),cols(ii));
  if ii>1
    % if ~isoctave; 
	if ~isoctave
      leg{ii}=['#' num2str(ii) ':' num2str(meastime(ii)) ' seconds later']; 
    else
      leg{ii}=['#' num2str(ii)]; 
    end
  end
end

for ii=1:Obj.API.M
  plot(20*log10(abs(fft(squeeze(Obj.Data.IR(ii,2,:)),Obj.Data.SamplingRate)))-20,cols(ii));  
end

xlim([-200 18200]);

axis([-200 18200 -65 15]);
leg{1}='#1, first measurement';
legend(leg);
title('Amplitude Spectra of Repeated Headphones Measurements (Left, Right)')
xlabel('Frequency (Hz)')
ylabel('Amplitude (dB)')





        if isoctave; fputs(fid, [ "just done some figure adaptations\n"]); end
        
        % set(gcf, 'Name', 'SOFAfile')
        % if isoctave; fputs(fid, [ "renamed figure\n"]); end
        print ("-r600", [SOFAfile '_1.png'])
        %print ("-r600", '/tmp/hrtf_1.png');
        if isoctave; fputs(fid, [ "just printed " SOFAfile "_1.png\n"]); end
end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer
