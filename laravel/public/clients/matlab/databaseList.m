% Displays the list of all visible Databases in the Ecosystem
%

serverURL='https://ecosystem.sonicom.eu/databases?type=json';

%% Fetch the list of databases from the Ecosystem
try
  options=weboptions; options.CertificateFilename=(''); 
  databases = webread(serverURL, options);
    % Check if the server returned a string instead of a struct/array.
  if ischar(databases)
    error('downloadFilesFromHTTPServer:serverError',...
          'Server returned a string, expected JSON.  Server response: %s', databases);
  end
catch ME
  error('downloadFilesFromHTTPServer:getFileList', ...
        'Failed to retrieve file list from server: %s.  Error: %s', serverURL, ME.message);
end

%% Check if correct JSON
if ~isstruct(databases) % check if structure
  error('downloadFilesFromHTTPServer:invalidFormat', 'Server did not return a struct of file information.');
end
if ~isfield(databases, 'data') % check if data in the structure
  error('downloadFilesFromHTTPServer:invalidFormat', 'Server did not return a JSON file information.');
end

%% Iterate through the database list and display each 
data=databases.data;
tab=struct2table(data);
disp(tab);