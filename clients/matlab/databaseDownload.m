function databaseDownload(databaseID, downloadPath)
% Downloads datafiles from the Ecosystem
%
% Parameters to be provided: 
%   databaseID: ID of the database, see databaseList
%   downloadPath: Local directory where the files will be downloaded.
%
% The local structure will be: downloadPath\datasetName\datasetDefName\DatafileName

%% Check if the download path exists
if ~isfolder(downloadPath)
  try
    mkdir(downloadPath);
  catch ME
    error('downloadFilesFromHTTPServer:createFolder', 'Failed to create download directory: %s.  Error: %s', downloadPath, ME.message);
  end
end

%% Fetch the list of files from the Ecosystem
serverURL=['https://sonicom.amtoolbox.org/databases/' num2str(databaseID) '/download?type=json'];
try
  options=weboptions; options.CertificateFilename=(''); 
  jsonData = webread(serverURL, options);
  if ischar(jsonData)
    error('downloadFilesFromHTTPServer:serverError','Server returned a string, expected JSON.  Server response: %s', jsonData);
  end
catch ME
  error('downloadFilesFromHTTPServer:getFileList', 'Failed to retrieve file list from server: %s.  Error: %s', serverURL, ME.message);
end

%% Check if correct JSON
if ~isstruct(jsonData) % check if structure
  error('downloadFilesFromHTTPServer:invalidFormat', 'Server did not return a struct of file information.');
end
if ~isfield(jsonData, 'data') % check if data in the structure
  error('downloadFilesFromHTTPServer:invalidFormat', 'Server did not return a JSON file information.');
end

%% Iterate through the datafile list and download each file
data=jsonData.data;
for ii = 1:length(data)
  fileURL = data(ii).URL; 
  fileName = data(ii).datafileName; %Get the file name
  if ~exist(fullfile(downloadPath, data(ii).datasetName),'dir'), mkdir(fullfile(downloadPath, data(ii).datasetName)); end
  if ~exist(fullfile(downloadPath, data(ii).datasetName, data(ii).datafileType),'dir'), mkdir(fullfile(downloadPath, data(ii).datasetName, data(ii).datafileType)); end  
  localFilePath = fullfile(downloadPath, data(ii).datasetName, data(ii).datafileType, fileName);
  
  try
    disp(['Downloading ' fileName ' from dataset ' data(ii).datasetName '...']);
    websave(localFilePath, fileURL, options);
  catch ME
    error('downloadFilesFromHTTPServer:downloadError', 'Failed to download file: %s from %s to %s. Error: %s', fileName, fileURL, localFilePath, ME.message);
  end
end
