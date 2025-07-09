%AnnotatedReceiver - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: support of AnnotatedReceiverAudio SOFA files implemented (22.04.2025)
% #Author: Michael Mihocic: support of AnnotatedReceiverAudio SOFA files implemented (08.07.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function AnnotatedReceiver(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="AnnotatedReceiver.log";
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

SaveSOFAproperties(Obj, SOFAfile)
if isoctave; fputs(fid, ["Successfully saved SOFA details to csv files\n"]); end

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
        print ('-dpng', "-r600", [SOFAfile '_1.png'])
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


end




function SaveSOFAproperties(Obj, SOFAfile)
%% get dimensions definitions
% dataDim = {}; % Initialize cell array
isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;
field_namesDim = fieldnames(Obj.API);
for i = 1:length(field_namesDim)
    field_name = field_namesDim{i};

    if ~startsWith(field_name, 'Dimensions')
        switch field_name
            case 'R'
                % description = 'Number of receivers or harmonic coefficients describing receivers';
                dimR = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'E'
                % description = 'Number of emitters or harmonic coefficients describing emitters';
                dimE = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'M'
                % description = 'Number of measurements';
                dimM = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'N'
                % description = 'Number of data samples describing one measurement';
                dimN = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'S'
                % description = 'Number of characters in a string';
                dimS = get_sofa_field(Obj.API, field_name, 'unknown');

            otherwise
                % description = '';
        end

        % if ~isempty(description)
        %     dataDim{end+1, 1} = [description];
        %     % data{end+1, 1} = ['Dimension ' description];
        %     dataDim{end, 2} = get_sofa_field(Obj.API, field_name, 'unknown');
        % end
    end
end
% if isoctave; fputs(log_fid, "Collected dimensions definitions from SOFA file.\n"); end


%% Write dimensions data to CSV using Octave's file I/O
header = {'SOFA Conventions', 'R', 'E', 'M', 'N', 'S'}; % Define header
dataDim = {[Obj.GLOBAL_SOFAConventions ' ' Obj.GLOBAL_SOFAConventionsVersion], dimR, dimE, dimM, dimN, dimS}; % Define data
output_filename = [SOFAfile, '_dim.csv']; % Construct output filename
delimiter = ';'; % Define delimiter
% if isoctave; fputs(log_fid, ["Attempting to write CSV: " output_filename "\n"]); end
csv_fid = fopen(output_filename, 'w'); % Open file for writing
if csv_fid < 0
    % if isoctave; fputs(log_fid, ["Error opening output CSV file: " output_filename "\n"]); fclose(log_fid); end
    error('Cannot open output file for writing: %s', output_filename);
end
try
    % Write header
    % fprintf(csv_fid, '%s%s%s%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});
    fprintf(csv_fid, '%s%s%s%s%s%s%s%s%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});

    % if isoctave; fputs(log_fid, "CSV header written.\n"); end
    fprintf(csv_fid, '%s%s%s%s%s%s%s%s%s%s%s\n', dataDim{1}, delimiter, dataDim{2}, delimiter, dataDim{3}, delimiter, dataDim{4}, delimiter, dataDim{5}, delimiter, dataDim{6});

    % if isoctave; fputs(log_fid, ["Data rows written to CSV.\n"]); end
catch ME
    fclose(csv_fid); % Close file even if error occurs during writing
    % if isoctave; fputs(log_fid, ["Error writing CSV data: " ME.message "\n"]); fclose(log_fid); end
    error('Error writing data to CSV file "%s\": %s', output_filename, ME.message);
end
fclose(csv_fid); % Close the output file successfully
% if isoctave; fputs(log_fid, ["Successfully saved SOFA details to " output_filename "\n"]); end
disp(['Successfully saved SOFA details to ' output_filename]);

%% Iterate over all fields in the SOFA object
dataProp = {}; % Initialize cell array
field_namesProp = fieldnames(Obj);
for i = 1:length(field_namesProp)
    field_name = field_namesProp{i};
    % Check if the field starts with 'GLOBAL_'
    if startsWith(field_name, 'GLOBAL_')
        dataProp{end+1, 1} = field_name(8:end);  % extractAfter is not supported in Octave
        dataProp{end, 2} = get_sofa_field(Obj, field_name, 'unknown');
    end
end
% if isoctave; fputs(log_fid, "Collected GLOBAL data from SOFA file.\n"); end

%% Write properties data to CSV using Octave's file I/O
header = {'Name', 'Value'}; % Define header
output_filename = [SOFAfile, '_prop.csv']; % Construct output filename
% delimiter = ';'; % Define delimiter
% if isoctave; fputs(log_fid, ["Attempting to write CSV: " output_filename "\n"]); end
csv_fid = fopen(output_filename, 'w'); % Open file for writing
if csv_fid < 0
    % if isoctave; fputs(log_fid, ["Error opening output CSV file: " output_filename "\n"]); fclose(log_fid); end
    error('Cannot open output file for writing: %s', output_filename);
end
try
    % Write header
    fprintf(csv_fid, '%s%s%s\n', header{1}, delimiter, header{2});
    % if isoctave; fputs(log_fid, "CSV header written.\n"); end
    % Write data rows
    for i = 1:size(dataProp, 1)
        % Ensure both elements are strings before writing
        name_str = dataProp{i, 1};
        value_str = dataProp{i, 2};
        if ~ischar(name_str); name_str = num2str(name_str); end % Convert if not char
        if ~ischar(value_str); value_str = num2str(value_str); end % Convert if not char
        % Robust CSV quoting and newline removal:
        name_str = ['"', strrep(strrep(name_str, char(10), ' '), char(13), ' '), '"']; % Quote, replace LF and CR with space
        value_str = ['"', strrep(strrep(value_str, char(10), ' '), char(13), ' '), '"']; % Quote, replace LF and CR with space
        fprintf(csv_fid, '%s%s%s\n', name_str, delimiter, value_str);
    end
    % if isoctave; fputs(log_fid, ["Data rows written to CSV.\n"]); end
catch ME
    fclose(csv_fid); % Close file even if error occurs during writing
    % if isoctave; fputs(log_fid, ["Error writing CSV data: " ME.message "\n"]); fclose(log_fid); end
    error('Error writing data to CSV file "%s\": %s', output_filename, ME.message);
end
fclose(csv_fid); % Close the output file successfully
% if isoctave; fputs(log_fid, ["Successfully saved SOFA details to " output_filename "\n"]); end
disp(['Successfully saved SOFA details to ' output_filename]);

end

%% Helper function to safely get fields
function value = get_sofa_field(obj, field_name, default_value)

if isfield(obj, field_name)
    value = obj.(field_name);
    % Convert numeric values to string for consistent CSV output
    if isnumeric(value)
        value = num2str(value);
    end
else
    value = default_value;
end
end

