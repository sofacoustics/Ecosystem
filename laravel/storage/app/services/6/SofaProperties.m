% SofaProperties - Function to load SOFA files, create and save visualizing 1 figure
% #Author: Michael Mihocic: SofaProperties extracted and stored (15.04.2025)
% #Author: Michael Mihocic: Modified for Octave compatibility (25.04.2025)
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
    %% Collect details
    if isoctave; fputs(log_fid, "About to extract details.\n"); end
    data = {}; % Initialize cell array
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
    % Iterate over all fields in the SOFA object
    field_names = fieldnames(Obj);
    for i = 1:length(field_names)
        field_name = field_names{i};
        % Check if the field starts with 'GLOBAL_'
        if startsWith(field_name, 'GLOBAL_')
            data{end+1, 1} = field_name;
            data{end, 2} = get_sofa_field(Obj, field_name, 'unknown');
        end
    end
    if isoctave; fputs(log_fid, "Collected data from SOFA file.\n"); end
    %% Write data to CSV using Octave's file I/O
    header = {'Name', 'Value'}; % Define header
    output_filename = [SOFAfile, '_1.csv']; % Construct output filename
    delimiter = ';'; % Define delimiter
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
        for i = 1:size(data, 1)
            % Ensure both elements are strings before writing
            name_str = data{i, 1};
            value_str = data{i, 2};
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
