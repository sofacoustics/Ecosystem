%BRIRGeometry - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: support of SingleRoomMIMOSRIR SOFA files implemented (11.04.2025)
% #Author: Michael Mihocic: conventions restriction removed (03.06.2025)
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

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="BRIRGeometry.log";
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
        disp('The SOFA file name SOFAfile is empty.');
        if isoctave; fputs(fid, [ "The SOFA file name SOFAfile is empty.\n"]); end
    else
        if isoctave; fputs(fid, [ "File: " SOFAfile "\n"]); end
    end
else
    % Use command line parameter for SOFAfile
    % disp("SOFAfile does not exist");
    disp(argv);
    arg_list = argv();
    fn = arg_list{1};
    disp(fn);
    SOFAfile = fn;
    if isoctave; fputs(fid, [ "File: " SOFAfile " or " fn]); end
end
%disp(["SOFAfile = " SOFAfile]);

%% Load SOFA file
%disp(['Loading: ' SOFAfile]);
Obj=SOFAload(SOFAfile);

if isoctave; fputs(fid, [ "About to plot\n"]); end

%% Plot a few figures
% switch Obj.GLOBAL_SOFAConventions
    % differ cases, depending on SOFA conventions

    % case 'MultiSpeakerBRIR', 'SingleRoomMIMOSRIR';
        % if isoctave; fputs(fid, [ "case SingleRoomMIMOSRIR\n"]); end
        [Obj] = SOFAupgradeConventions(Obj);
        % figure('Name',SOFAfile);
        if isoctave; fputs(fid, [ "just done SOFA upgrade\n"]); end
        % SOFAplotHRTF(Obj,'ETCHorizontal',1);
        SOFAplotGeometry(Obj);
        if isoctave; fputs(fid, [ "just done SOFAplotGeometry\n"]); end
        set(gcf, 'Name', 'SOFAfile')
        if isoctave; fputs(fid, [ "renamed figure\n"]); end
        view(45,30);
        if isoctave; fputs(fid, [ "adapted view\n"]); end
        set(gcf, 'Position', [300, 500, 800, 500]);
        if isoctave; fputs(fid, [ "adapted position\n"]); end
        if isoctave; fputs(fid, [ "trying to print " SOFAfile "_1.png\n"]); end

        % plot(rand(10))
        print ("-r600", ['TESTPRINT_1.png']); % TO BE REMOVED
        if isoctave; fputs(fid, [ "just printed TESTPRINT_1.png\n"]); end % TO BE REMOVED
        print ("-r600", [SOFAfile '_1.png']);
        %print ("-r600", '/tmp/hrtf_1.png');
        if isoctave; fputs(fid, [ "just printed " SOFAfile "_1.png\n"]); end
% end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer
