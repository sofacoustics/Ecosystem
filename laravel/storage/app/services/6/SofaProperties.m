%SofaProperties - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: SofaProperties extracted and stored (15.04.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function SofaProperties(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="/home/sonicom/isf-sonicom-laravel/laravel/storage/app/tools/1/SofaProperties.log"
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

if isoctave; fputs(fid, [ "About to extract details\n"]); end

%% Collect details
data = {};


data{end+1,1} = 'Title';
if isfield(Obj, 'GLOBAL_Title')
    data{end,2} = Obj.GLOBAL_Title;
else
    data{end,2} = 'unknown';
end

data{end+1,1} = 'Conventions';
if isfield(Obj, 'GLOBAL_SOFAConventions')
    data{end,2} = Obj.GLOBAL_SOFAConventions;
else
    data{end,2} = 'unknown';
end

data{end+1,1} = 'Conventions Version';
if isfield(Obj, 'GLOBAL_SOFAConventionsVersion')
    data{end,2} = Obj.GLOBAL_SOFAConventionsVersion;
else
    data{end,2} = 'unknown';
end

data{end+1,1} = 'References';
if isfield(Obj, 'GLOBAL_References')
    data{end,2} = Obj.GLOBAL_References;
else
    data{end,2} = 'unknown';
end

data{end+1,1} = 'Author';
if isfield(Obj, 'GLOBAL_AuthorContact')
    data{end,2} = Obj.GLOBAL_AuthorContact;
else
    data{end,2} = 'unknown';
end

data{end+1,1} = 'Not existing';
if isfield(Obj, 'GLOBAL_notexisting')
    data{end,2} = Obj.GLOBAL_notexisting;
else
    data{end,2} = 'unknown';
end

% data{3,1} = 'Description';
% data{3,2} = Obj.GLOBAL_Description;
if isoctave; fputs(fid, [ "collected data from SOFA file\n"]); end

% define header
header = {'Name', 'Value'};
% convert to table
T = cell2table(data, 'VariableNames', header);
if isoctave; fputs(fid, [ "converted data to table\n"]); end
% write to csv
writetable(T, string(SOFAfile) + '_1.csv', 'Delimiter', ';');
if isoctave; fputs(fid, [ "just saved SOFA details to " SOFAfile "_1.csv\n"]); end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer
