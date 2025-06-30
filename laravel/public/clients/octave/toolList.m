% Displays the list of all visible tools in the Ecosystem
%

serverURL='https://ecosystem.sonicom.eu/tools?type=json';

%% Fetch the list of tools from the Ecosystem
try
  options=weboptions; options.CertificateFilename=(''); 
  tools = webread(serverURL, options);
    % Check if the server returned a string instead of a struct/array.
  if ischar(tools)
    error('downloadFilesFromHTTPServer:serverError',...
          'Server returned a string, expected JSON.  Server response: %s', tools);
  end
catch ME
  error('downloadFilesFromHTTPServer:getFileList', ...
        'Failed to retrieve file list from server: %s.  Error: %s', serverURL, ME.message);
end

%% Check if correct JSON
if ~isstruct(tools) % check if structure
  error('downloadFilesFromHTTPServer:invalidFormat', 'Server did not return a struct of file information.');
end
if ~isfield(tools, 'data') % check if data in the structure
  error('downloadFilesFromHTTPServer:invalidFormat', 'Server did not return a JSON file information.');
end

%% Iterate through the tool list and display each 
data=tools.data;
for ii = 1:length(data)
    toolID = data(ii).ID; % Get the tool ID
    toolURL = data(ii).URL; % Get the URL to JSON for download
    toolTitle = data(ii).Title; % Get the tool Title
    toolType = data(ii).Type; % Get the tool Type
    toolFilename = data(ii).Filename; % Get the tool Filename
    if isempty(toolFilename)
      disp(['Tool #ID ' num2str(toolID) ' (' toolType '): ' toolTitle '. File not available.']);
    else
      disp(['Tool #ID ' num2str(toolID) ' (' toolType '): ' toolTitle '. URL: ' toolURL]);
    end
end
