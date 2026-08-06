function databaseDownload(downloadPath, databaseID)
% Downloads datafiles from the SONICOM Ecosystem
%
% Parameters:
%   downloadPath: Local directory where the files will be downloaded.
%   databaseID:   ID of the database, see databaseList.
%
% The local structure will be:
%   downloadPath/datasetName/datafileType/datafileName

  %% Create the download directory
  if ~isfolder(downloadPath)
    [success, message] = mkdir(downloadPath);

    if ~success
      error('databaseDownload:createFolder', ...
            'Failed to create download directory: %s. Error: %s', ...
            char(downloadPath), char(message));
    end
  end

  %% Retrieve the file list from the server
  serverURL = sprintf( ...
    'https://ecosystem.sonicom.eu/databases/%d/download?type=json', ...
    databaseID);

  try
    response = webread(serverURL);

    % MATLAB may return a structure, while Octave may return JSON text
    if ischar(response) || (isstring(response) && isscalar(response))
      jsonData = jsondecode(char(response));
    else
      jsonData = response;
    end

  catch ME
    error('databaseDownload:getFileList', ...
          'Failed to retrieve file list from server: %s. Error: %s', ...
          char(serverURL), char(ME.message));
  end

  %% Validate the server response
  if ~isstruct(jsonData)
    error('databaseDownload:invalidFormat', ...
          'Server did not return a valid JSON structure.');
  end

  if ~isfield(jsonData, 'data')
    error('databaseDownload:invalidFormat', ...
          'JSON response does not contain a data field.');
  end

  %% Retrieve the datafile list
  data = jsonData.data;

  if isempty(data)
    disp('This database does not contain any datafiles.');
    return;
  end

  %% Download each datafile
  for ii = 1:length(data)

    % MATLAB converts invalid JSON field names into valid field names.
    % For example, "Datafile URL" becomes "DatafileURL".
    fileURL     = data(ii).DatafileURL;
    fileName    = data(ii).DatafileName;
    datasetName = data(ii).DatasetName;
    fileType    = data(ii).DatafileType;

    % Convert values to character vectors for MATLAB and Octave compatibility
    fileURL     = char(fileURL);
    fileName    = char(fileName);
    datasetName = char(datasetName);
    fileType    = char(fileType);

    % Encode spaces in URLs
    fileURL = strrep(fileURL, ' ', '%20');

    % Create the target directories
    datasetPath = fullfile(downloadPath, datasetName);
    typePath    = fullfile(datasetPath, fileType);

    if ~isfolder(datasetPath)
      [success, message] = mkdir(datasetPath);

      if ~success
        error('databaseDownload:createDatasetFolder', ...
              'Failed to create dataset directory: %s. Error: %s', ...
              datasetPath, message);
      end
    end

    if ~isfolder(typePath)
      [success, message] = mkdir(typePath);

      if ~success
        error('databaseDownload:createTypeFolder', ...
              'Failed to create datafile type directory: %s. Error: %s', ...
              typePath, message);
      end
    end

    % Create the local file path
    localFilePath = fullfile(typePath, fileName);

    disp(['Downloading ' fileName ...
          ' from dataset ' datasetName '...']);

    try
      % Use the appropriate download function for MATLAB or Octave
      if exist('OCTAVE_VERSION', 'builtin') ~= 0

        % GNU Octave download
        [~, success, message] = urlwrite(fileURL, localFilePath);

        if ~success
          error('Download failed: %s', message);
        end

      else

        % MATLAB download
        websave(localFilePath, fileURL);

      end

    catch ME
      error('databaseDownload:downloadError', ...
            'Failed to download file: %s from %s to %s. Error: %s', ...
            fileName, fileURL, localFilePath, char(ME.message));
    end
  end

  disp('Download completed.');
end