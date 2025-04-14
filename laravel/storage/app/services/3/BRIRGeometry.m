%BRIRGeometry - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: support of SingleRoomMIMOSRIR SOFA files implemented (11.04.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function BRIRGeometry(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

%jw:tmp logfile
logfile="/home/sonicom/isf-sonicom-laravel/laravel/storage/app/tools/1/BRIRGeometry.log"
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
    % differ cases, depending on SOFA conventions
%     case 'SimpleFreeFieldHRIR'
% 
%         fputs(fid, [ "case SimpleFreeFieldHRIR\n"]);
%         % plot ETC horizontal plane
%         figure('Name',SOFAfile);
%         fputs(fid, [ "just done figure\n"]);
%         SOFAplotHRTF(Obj,'ETCHorizontal',1);
%         fputs(fid, [ "just done SOFAplotHRTF\n"]);
%         print ("-r600", [SOFAfile '_1.png']);
%         %print ("-r600", '/tmp/hrtf_1.png');
%         fputs(fid, [ "just done print" SOFAfile "_1.png\n"]);
% 
% 
%         % plot magnitude spectrum in the median plane, channel 2
% %        figure('Name',SOFAfile);
% %        SOFAplotHRTF(Obj,'MagMedian',2);
% %        print ("-r600", [SOFAfile '_2.png']);
% %        fputs(fid, [ "just written " SOFAfile "_2.png\n"]);
% 
%         % plot non-normalized magnitude spectrum in the median plane, channel 1
% %        figure('Name',SOFAfile);
% %        SOFAplotHRTF(Obj,'MagMedian','nonormalization');
% %        print ("-r600", [SOFAfile '_3.png']);
% %        fputs(fid, [ "just written " SOFAfile "_3.png\n"]);
%         % plot geometry
%       %  SOFAplotGeometry(Obj);
%       %  title(['Geometry SimpleFreeFieldHRIR, ' num2str(Obj.API.M) ' position(s)'])
%       %  set(gcf, 'Name', SOFAfile);
%       %  print ("-r600", [SOFAfile '_4.png']);
% 
%         % plot geometry, only show every 45th measurement
%       %  index = 1:45:Obj.API.M;
%       %  SOFAplotGeometry(Obj,index);
%       %  title(['Geometry SimpleFreeFieldHRIR, reduced to ' num2str(size(index,2)) ' position(s)'])
%       %  set(gcf, 'Name', SOFAfile);
%       %  print ("-r600", [SOFAfile '_5.png']);
% 
%     case 'GeneralTF'
%         fputs(fid, [ "case GeneralTF\n"]);
%         % plot magnitude spectrum in the median plane, channel 1
%         figure('Name',SOFAfile);
%         SOFAplotHRTF(Obj,'MagMedian',1,'conversion2ir');
%         print ("-r600", [SOFAfile '_1.png']);
% 
%         figure('Name',mfilename);
%         SOFAplotHRTF(Obj,'MagMedian',1,'noconversion2ir');
%         print ("-r600", [SOFAfile '_2.png']);
% 
% 
%     case 'GeneralFIR'
%         fputs(fid, [ "case GeneralFIR\n"]);
%         SOFAplotGeometry(Obj);
%         title(['Geometry GeneralFIR, ' num2str(Obj.API.R) ' receiver(s), ' num2str(Obj.API.M) ' position(s)'])
%         set(gcf, 'Name', mfilename);
%         print ("-r600", [SOFAfile '_1.png']);
% 
%     case 'AnnotatedReceiverAudio'
%         % no plan yet for this convention ;-)

    case 'MultiSpeakerBRIR', 'SingleRoomMIMOSRIR';
        if isoctave; fputs(fid, [ "case SingleRoomMIMOSRIR\n"]); end
        [Obj] = SOFAupgradeConventions(Obj);
        % figure('Name',SOFAfile);
        if isoctave; fputs(fid, [ "just done figure\n"]); end
        % SOFAplotHRTF(Obj,'ETCHorizontal',1);
        SOFAplotGeometry(Obj);
        view(45,30);
        set(gcf, 'Position', [300, 500, 800, 500]);
        if isoctave; fputs(fid, [ "just done SOFAplotHRTF\n"]); end
        print ("-r600", [SOFAfile '_1.png']);
        %print ("-r600", '/tmp/hrtf_1.png');
        if isoctave; fputs(fid, [ "just done print" SOFAfile "_1.png\n"]); end
end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer
