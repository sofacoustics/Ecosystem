function databaseList()
% Displays all visible databases in the SONICOM Ecosystem
% using a formatted text table.

  %% Define the server URL
  serverURL = 'https://ecosystem.sonicom.eu/databases?type=json';

  %% Retrieve the database list from the server
  try
    response = webread(serverURL);

    % MATLAB may return a structure, while Octave may return JSON text
    if ischar(response) || (isstring(response) && isscalar(response))
      databases = jsondecode(char(response));
    else
      databases = response;
    end

  catch ME
    error('databaseList:getFileList', ...
          'Failed to retrieve database list from server: %s. Error: %s', ...
          char(serverURL), char(ME.message));
  end

  %% Validate the server response
  if ~isstruct(databases)
    error('databaseList:invalidFormat', ...
          'Server did not return a valid JSON structure.');
  end

  if ~isfield(databases, 'data')
    error('databaseList:invalidFormat', ...
          'JSON response does not contain a data field.');
  end

  %% Retrieve the database list
  data = databases.data;

  if isempty(data)
    disp('The server did not return any databases.');
    return;
  end

  %% Define table column widths
  idWidth       = 5;
  titleWidth    = 30;
  subtitleWidth = 48;
  yearWidth     = 14;
  createdWidth  = 27;
  updatedWidth  = 27;

  separator = [ ...
    repmat('-', 1, idWidth),       '-+-', ...
    repmat('-', 1, titleWidth),    '-+-', ...
    repmat('-', 1, subtitleWidth), '-+-', ...
    repmat('-', 1, yearWidth),     '-+-', ...
    repmat('-', 1, createdWidth),  '-+-', ...
    repmat('-', 1, updatedWidth)];

  %% Display table heading
  fprintf('\nSONICOM Ecosystem Databases\n\n');
  fprintf('%-*s | %-*s | %-*s | %-*s | %-*s | %-*s\n', ...
          idWidth,       'ID', ...
          titleWidth,    'Title', ...
          subtitleWidth, 'Subtitle', ...
          yearWidth,     'Production Year', ...
          createdWidth,  'Created Date', ...
          updatedWidth,  'Updated Date');

  fprintf('%s\n', separator);

  %% Display each database
  for ii = 1:length(data)

    % Read mandatory fields
    databaseID    = data(ii).ID;
    databaseTitle = convertToText(data(ii).Title);
    databaseURL   = convertToText(data(ii).URL);

    % Read optional fields
    if isfield(data(ii), 'Subtitle')
      subtitle = convertToText(data(ii).Subtitle);
    else
      subtitle = '';
    end

    if isfield(data(ii), 'ProductionYear')
      productionYear = convertToText(data(ii).ProductionYear);
    else
      productionYear = '';
    end

    if isfield(data(ii), 'CreatedDate')
      createdDate = convertToText(data(ii).CreatedDate);
    else
      createdDate = '';
    end

    if isfield(data(ii), 'UpdatedDate')
      updatedDate = convertToText(data(ii).UpdatedDate);
    else
      updatedDate = '';
    end

    % Convert the database ID to text
    databaseIDText = num2str(databaseID);

    % Shorten long values to keep the table readable
    databaseIDText = shortenText(databaseIDText, idWidth);
    databaseTitle = shortenText(databaseTitle, titleWidth);
    subtitle      = shortenText(subtitle, subtitleWidth);
    productionYear = shortenText(productionYear, yearWidth);
    createdDate   = shortenText(createdDate, createdWidth);
    updatedDate   = shortenText(updatedDate, updatedWidth);

    % Print the database row
    fprintf('%-*s | %-*s | %-*s | %-*s | %-*s | %-*s\n', ...
            idWidth,       databaseIDText, ...
            titleWidth,    databaseTitle, ...
            subtitleWidth, subtitle, ...
            yearWidth,     productionYear, ...
            createdWidth,  createdDate, ...
            updatedWidth,  updatedDate);

    % Print the complete URL below the corresponding row
    fprintf('      URL: %s\n', databaseURL);
  end

  fprintf('%s\n', separator);
  fprintf('Total databases: %d\n\n', length(data));
end


function textValue = convertToText(value)
% Converts a JSON value into a single-row character vector.

  % Handle JSON null, empty arrays, and empty values
  if isempty(value)
    textValue = '';
    return;
  end

  % Convert the value to a character vector
  if ischar(value)
    textValue = value;
  elseif isstring(value)
    textValue = char(value);
  elseif isnumeric(value)
    textValue = num2str(value);
  else
    textValue = char(value);
  end

  % Ensure that the result is always a single row
  textValue = textValue(:).';
end


function textValue = shortenText(textValue, maximumLength)
% Shortens a text value to fit into a table column.

  textValue = convertToText(textValue);

  if length(textValue) > maximumLength
    if maximumLength > 3
      textValue = [textValue(1:maximumLength - 3) '...'];
    else
      textValue = textValue(1:maximumLength);
    end
  end
end