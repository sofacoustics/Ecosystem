% Downloads datafiles from the Ecosystem
%
% Parameter to be provided: 
%   serverURL    - URL to the JSON Database File List
%   downloadPath - Local directory where the files will be downloaded.
%
%
serverURL='https://sonicom.amtoolbox.org/databases/1/download?type=json';
downloadPath='temp';

%% Check if the download path exists
if ~isfolder(downloadPath)
  try
    mkdir(downloadPath);
  catch ME
    error('downloadFilesFromHTTPServer:createFolder', ...
          'Failed to create download directory: %s.  Error: %s', downloadPath, ME.message);
  end
end

%% Fetch the list of files from the Ecosystem
try
    options=weboptions; options.CertificateFilename=(''); 
    jsonData = webread(serverURL, options);
      % Check if the server returned a string instead of a struct/array.
    if ischar(jsonData)
        error('downloadFilesFromHTTPServer:serverError',...
              'Server returned a string, expected JSON.  Server response: %s', jsonData);
    end
catch ME
    error('downloadFilesFromHTTPServer:getFileList', ...
          'Failed to retrieve file list from server: %s.  Error: %s', serverURL, ME.message);
end

%% Check if jsonData is a struct
if ~isstruct(jsonData) && ~iscell(jsonData)
    error('downloadFilesFromHTTPServer:invalidFormat',...
          'Server did not return a struct or cell array of file information.');
end

%% Iterate through the datafile list and download each file
if isstruct(jsonData)
  if isfield(jsonData, 'data')
    data=jsonData.data;
    for ii = 1:length(data)
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
