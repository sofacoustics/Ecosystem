% downloadFilesFromHTTPServer Downloads files from an HTTP server,
% where the list of files is provided by the server.
%
%   serverURL    - URL of the server providing the file list (e.g., 'https://example.com/api/files').
%   downloadPath - Local directory where the files will be downloaded.
%
% Example usage:
%   serverURL = 'https://example.com/api/files'; % Replace with actual server URL
%   downloadPath = 'C:\MyDownloads';          % Replace with desired local path
%   downloadFilesFromHTTPServer(serverURL, downloadPath);

serverURL='https://sonicom.amtoolbox.org/databases/1/download?type=json';
downloadPath='';

%% Check if the download path exists
if ~isfolder(downloadPath)
    try
        mkdir(downloadPath);
    catch ME
        error('downloadFilesFromHTTPServer:createFolder', ...
              'Failed to create download directory: %s.  Error: %s', downloadPath, ME.message);
    end
end

%% Fetch the list of files from the server (JSON assumed)
try
    options=weboptions; options.CertificateFilename=(''); 
    jsonData = webread(serverURL, options);
    % Check if the server returned a string instead of a struct/array.
    if ischar(jsonData)
        error('downloadFilesFromHTTPServer:serverError',...
              'Server returned a string, expected JSON.  Server response: %s', jsonData);
    end
    fileList = jsonData; % Assuming the server returns a JSON array of file information
catch ME
    error('downloadFilesFromHTTPServer:getFileList', ...
          'Failed to retrieve file list from server: %s.  Error: %s', serverURL, ME.message);
end

%% Check if fileList is a struct
if ~isstruct(fileList) && ~iscell(fileList)
    error('downloadFilesFromHTTPServer:invalidFormat',...
          'Server did not return a struct or cell array of file information.');
end

%% Iterate through the file list and download each file
if isstruct(fileList)
  if isfield(fileList, 'data')
    data=fileList.data;
    fileFields = fieldnames(data); %get the field names.
    for ii = 1:numel(fileFields)
        fileURL = data(ii).URL; % Adjust field name as needed (e.g., 'url', 'download_url')
        fileName = data(ii).datafileName; %Get the file name
        if isempty(fileURL)
            warning('downloadFilesFromHTTPServer:emptyURL',...
                  'URL is empty for file %s, skipping download.',fileName);
            continue;
        end
        localFilePath = fullfile(downloadPath, fileName);
        try
            disp(['Downloading ' fileName ' from dataset ' data(ii).datasetName '...']);
            websave(localFilePath, fileURL, options);
            % disp(['Downloaded ' fileName ' to ' localFilePath]);
        catch ME
            error('downloadFilesFromHTTPServer:downloadError', ...
                  'Failed to download file: %s from %s to %s. Error: %s', fileName, fileURL, localFilePath, ME.message);
        end
    end
  end
end
