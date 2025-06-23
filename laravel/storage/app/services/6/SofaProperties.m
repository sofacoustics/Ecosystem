% SofaProperties - Function to load SOFA files, create and save visualizing 1 figure
% #Author: Michael Mihocic: SofaProperties extracted and stored (15.04.2025)
% #Author: Michael Mihocic: Modified for Octave compatibility (25.04.2025)
% #Author: Michael Mihocic: Dimensions displayed, 2 tables created (23.06.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing permissions and limitations under the License.

function SofaProperties(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile = 'hrtf_nh4.sofa';
% Check if running in Octave
isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;
% Log file setup
logfile = "SofaProperties.log";
log_fid = fopen(logfile, "w"); % Use a different handle for log file
if log_fid < 0
    error('Cannot open log file: %s', logfile);
end
s = pwd;
disp(["pwd = " s]);
if isoctave; fputs(log_fid, ["pwd = " s "\n"]); end % Log pwd
%% Prologue: (un)comment here if you want to:
% clc; % clean-up first
close all; % clean-up first
tic; % timer
SOFAstart; % remove this optionally
% Suppress warnings (consider being more specific if possible)
warning('off');
if isoctave; fputs(log_fid, "Warnings disabled.\n"); end
% Check if SOFAfile argument is provided
if nargin < 1 || isempty(SOFAfile)
    disp('SOFAfile argument not provided or empty. Trying command line arguments...');
    if isoctave; fputs(log_fid, "SOFAfile argument missing, checking argv().\n"); end
    arg_list = argv();
    if length(arg_list) >= 1
        SOFAfile = arg_list{1};
        disp(['Using command line argument: ' SOFAfile]);
        if isoctave; fputs(log_fid, ["Using command line arg: " SOFAfile "\n"]); end
    else
        error('No SOFA file specified either as function argument or command line argument.');
        if isoctave; fputs(log_fid, "Error: No SOFA file specified.\n"); fclose(log_fid); end % Close log file on error
    end
else
    disp(['Using function argument: ' SOFAfile]);
    if isoctave; fputs(log_fid, ["Using function arg: " SOFAfile "\n"]); end
end
%% Load SOFA file
disp(['Loading: ' SOFAfile]);
if isoctave; fputs(log_fid, ['Loading: ' SOFAfile '\n']); end
try
    Obj = SOFAload(SOFAfile); % Ensure SOFAload function is accessible
catch ME
    if isoctave; fputs(log_fid, ["Error loading SOFA file: " ME.message "\n"]); fclose(log_fid); end
    error('Failed to load SOFA file "%s\": %s', SOFAfile, ME.message);
end
if isoctave; fputs(log_fid, "SOFA file loaded successfully.\n"); end

%% Collect all details
if isoctave; fputs(log_fid, "About to extract details.\n"); end

% Helper function to safely get fields
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
% Populate data cell array


%% get dimensions definitions
% dataDim = {}; % Initialize cell array
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
if isoctave; fputs(log_fid, "Collected dimensions definitions from SOFA file.\n"); end


%% Write dimensions data to CSV using Octave's file I/O
header = {'SOFA Conventions', 'Dim R', 'Dim E', 'Dim M', 'Dim N', 'Dim S'}; % Define header
dataDim = {[Obj.GLOBAL_SOFAConventions ' ' Obj.GLOBAL_SOFAConventionsVersion], dimR, dimE, dimM, dimN, dimS}; % Define data
output_filename = [SOFAfile, '_dim.csv']; % Construct output filename
delimiter = ';'; % Define delimiter
if isoctave; fputs(log_fid, ["Attempting to write CSV: " output_filename "\n"]); end
csv_fid = fopen(output_filename, 'w'); % Open file for writing
if csv_fid < 0
    if isoctave; fputs(log_fid, ["Error opening output CSV file: " output_filename "\n"]); fclose(log_fid); end
    error('Cannot open output file for writing: %s', output_filename);
end
try
    % Write header
    % fprintf(csv_fid, '%s%s%s%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});
    fprintf(csv_fid, '%s%s%s%s%s%s%s%s%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});
    
    if isoctave; fputs(log_fid, "CSV header written.\n"); end
    fprintf(csv_fid, '%s%s%s%s%s%s%s%s%s%s%s\n', dataDim{1}, delimiter, dataDim{2}, delimiter, dataDim{3}, delimiter, dataDim{4}, delimiter, dataDim{5}, delimiter, dataDim{6});

    % fprintf(csv_fid, '%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});

    % % Write data rows
    % for i = 1:size(dataDim, 1)
    %     % Ensure both elements are strings before writing
    %     name_str = dataDim{i, 1};
    %     value_str = dataDim{i, 2};
    %     if ~ischar(name_str); name_str = num2str(name_str); end % Convert if not char
    %     if ~ischar(value_str); value_str = num2str(value_str); end % Convert if not char
    %     % Robust CSV quoting and newline removal:
    %     name_str = ['"', strrep(strrep(name_str, char(10), ' '), char(13), ' '), '"']; % Quote, replace LF and CR with space
    %     value_str = ['"', strrep(strrep(value_str, char(10), ' '), char(13), ' '), '"']; % Quote, replace LF and CR with space
    %     fprintf(csv_fid, '%s%s%s\n', name_str, delimiter, value_str);
    % end
    if isoctave; fputs(log_fid, ["Data rows written to CSV.\n"]); end
catch ME
    fclose(csv_fid); % Close file even if error occurs during writing
    if isoctave; fputs(log_fid, ["Error writing CSV data: " ME.message "\n"]); fclose(log_fid); end
    error('Error writing data to CSV file "%s\": %s', output_filename, ME.message);
end
fclose(csv_fid); % Close the output file successfully
if isoctave; fputs(log_fid, ["Successfully saved SOFA details to " output_filename "\n"]); end
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
if isoctave; fputs(log_fid, "Collected GLOBAL data from SOFA file.\n"); end


% % get dimensions of objects, use function to scan sub-structures
% data = process_fields(Obj.API.Dimensions, 'Dimension', data, 1);
% if isoctave; fputs(log_fid, "Collected dimensions of objects from SOFA file.\n"); end




%% Write properties data to CSV using Octave's file I/O
header = {'Name', 'Value'}; % Define header
output_filename = [SOFAfile, '_prop.csv']; % Construct output filename
% delimiter = ';'; % Define delimiter
if isoctave; fputs(log_fid, ["Attempting to write CSV: " output_filename "\n"]); end
csv_fid = fopen(output_filename, 'w'); % Open file for writing
if csv_fid < 0
    if isoctave; fputs(log_fid, ["Error opening output CSV file: " output_filename "\n"]); fclose(log_fid); end
    error('Cannot open output file for writing: %s', output_filename);
end
try
    % Write header
    fprintf(csv_fid, '%s%s%s\n', header{1}, delimiter, header{2});
    if isoctave; fputs(log_fid, "CSV header written.\n"); end
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
    if isoctave; fputs(log_fid, ["Data rows written to CSV.\n"]); end
catch ME
    fclose(csv_fid); % Close file even if error occurs during writing
    if isoctave; fputs(log_fid, ["Error writing CSV data: " ME.message "\n"]); fclose(log_fid); end
    error('Error writing data to CSV file "%s\": %s', output_filename, ME.message);
end
fclose(csv_fid); % Close the output file successfully
if isoctave; fputs(log_fid, ["Successfully saved SOFA details to " output_filename "\n"]); end
disp(['Successfully saved SOFA details to ' output_filename]);


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fputs(log_fid, "Script finished.\n"); fclose(log_fid); end % Close log file at the end
toc; % timer
end % End of function SofaProperties


% function data = process_fields(s, prefix, data, depth)
%     fields = fieldnames(s);
%     for i = 1:length(fields)
%         field = fields{i};
%         value = getfield(s, field);
%
%         % Je nach Tiefe Leerzeichen oder Punkt verwenden
%         if depth == 1
%             full_name = [prefix ' ' field];
%         else
%             full_name = [prefix '.' field];
%         end
%
%         if isstruct(value)
%             data = process_fields(value, full_name, data, depth + 1);
%         else
%             data{end+1, 1} = full_name;
%             data{end, 2} = value;
%         end
%     end
% end