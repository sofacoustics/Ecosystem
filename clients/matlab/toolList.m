% Displays the list of all visible tools in the Ecosystem
%

serverURL='https://sonicom.amtoolbox.org/tools?type=json';

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
tab=struct2table(data);
disp(tab);