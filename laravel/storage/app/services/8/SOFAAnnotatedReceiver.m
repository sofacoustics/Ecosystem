%SOFAAnnotatedReceiver - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: support of AnnotatedReceiverAudio SOFA files implemented (22.04.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function SOFAAnnotatedReceiver(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="SOFAAnnotatedReceiver.log";
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
    case 'AnnotatedReceiverAudio';
        if isoctave; fputs(fid, [ "case AnnotatedReceiverAudio\n"]); end
        % figure('Name',SOFAfile);
        [azi, ele, ~] = cart2sph(Obj.ListenerView(:,1), Obj.ListenerView(:,2), Obj.ListenerView(:,3));
        azi_deg = rad2deg(azi);
        ele_deg = rad2deg(ele);

        %% Plot ListenerView (azi, ele)
        % time = (1:length(azi))/Obj.Data.SamplingRate;
        figure('Name',mfilename);
        subplot(2,1,1); hold on;
        plot(Obj.M,azi_deg,'LineWidth',2); % plot azimuthal trajectory
        ylabel('Azimuth (deg)');
        title('AnnotatedReceiverAudio: ListenerView');

        % time = (1:length(ele))/Obj.Data.SamplingRate;
        subplot(2,1,2); hold on;
        % ele_time = linspace(time(1), time(end), length(ele));  % ergibt [0.01 0.025 0.04]
        plot(Obj.M, ele_deg, 'LineWidth', 2);  % plot elevational trajectory
        % plot(time,ele,'LineWidth',2); % plot elevational trajectory
        ylabel('Elevation (deg)');
        xlabel('Time (s)');

        if isoctave; fputs(fid, [ "just done figure\n"]); end;
        
        % set(gcf, 'Name', 'SOFAfile')
        % if isoctave; fputs(fid, [ "renamed figure\n"]); end
        print ("-r600", [SOFAfile '_1.png'])
        %print ("-r600", '/tmp/hrtf_1.png');
        if isoctave; fputs(fid, [ "just printed " SOFAfile "_1.png\n"]); end
end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave 
    fputs(fid,  "DONE");
    fclose(fid); 
end

toc; % timer
