function toolList()
% Displays the list of all visible tools in the SONICOM Ecosystem
% using a formatted text table.

  %% Define the server URL
  serverURL = 'https://ecosystem.sonicom.eu/tools?type=json';

  %% Retrieve the tool list from the server
  try
    response = webread(serverURL);

    % MATLAB may return a structure, while Octave may return JSON text
    if ischar(response) || (isstring(response) && isscalar(response))
      tools = jsondecode(char(response));
    else
      tools = response;
    end

  catch ME
    error('toolList:getFileList', ...
          'Failed to retrieve tool list from server: %s. Error: %s', ...
          char(serverURL), char(ME.message));
  end

  %% Validate the server response
  if ~isstruct(tools)
    error('toolList:invalidFormat', ...
          'Server did not return a valid JSON structure.');
  end

  if ~isfield(tools, 'data')
    error('toolList:invalidFormat', ...
          'JSON response does not contain a data field.');
  end

  %% Retrieve the tool list
  data = tools.data;

  if isempty(data)
    disp('The server did not return any tools.');
    return;
  end

  %% Define table column widths
  idWidth       = 3;
  typeWidth     = 10;
  titleWidth    = 30;
  fileWidth     = 30;
  urlWidth      = 46;

  separator = [ ...
    repmat('-', 1, idWidth),   '-+-', ...
    repmat('-', 1, typeWidth), '-+-', ...
    repmat('-', 1, titleWidth),'-+-', ...
    repmat('-', 1, fileWidth), '-+-', ...
    repmat('-', 1, urlWidth)];

  %% Display table heading
  fprintf('\nSONICOM Ecosystem Tools\n\n');
  fprintf('%-*s | %-*s | %-*s | %-*s | %-*s\n', ...
          idWidth,   'ID', ...
          typeWidth, 'Type', ...
          titleWidth,'Title', ...
          fileWidth, 'Filename', ...
          urlWidth,  'URL');

  fprintf('%s\n', separator);

  %% Display each tool
  for ii = 1:length(data)

    toolID    = data(ii).ID;
    toolTitle = convertToText(data(ii).Title);
    toolType  = convertToText(data(ii).Type);

    if isfield(data(ii), 'Filename')
      toolFilename = convertToText(data(ii).Filename);
    else
      toolFilename = '';
    end

    if isfield(data(ii), 'URL')
      toolURL = convertToText(data(ii).URL);
    else
      toolURL = '';
    end

    toolIDText = num2str(toolID);

    toolIDText    = shortenText(toolIDText, idWidth);
    toolType      = shortenText(toolType, typeWidth);
    toolTitle     = shortenText(toolTitle, titleWidth);
    toolFilename  = shortenText(toolFilename, fileWidth);
    % toolURL       = shortenText(toolURL, urlWidth);

    fprintf('%-*s | %-*s | %-*s | %-*s | %-*s\n', ...
            idWidth,   toolIDText, ...
            typeWidth, toolType, ...
            titleWidth, toolTitle, ...
            fileWidth, toolFilename, ...
            urlWidth,  toolURL);
  end

  fprintf('%s\n', separator);
  fprintf('Total tools: %d\n\n', length(data));
end


function textValue = convertToText(value)
% Converts a JSON value into a single-row character vector.

  if isempty(value)
    textValue = '';
    return;
  end

  if ischar(value)
    textValue = value;
  elseif isstring(value)
    textValue = char(value);
  elseif isnumeric(value)
    textValue = num2str(value);
  else
    textValue = char(value);
  end

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