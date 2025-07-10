%DirectivityGeneral - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: support of Directivity SOFA files implemented (15.04.2025)
% #Author: Michael Mihocic: conventions restriction removed (03.06.2025)
% #Author: Michael Mihocic: file renamed; attempting to create new figures, still work in progress... (27.06.2025)
% #Author: Michael Mihocic: figure creation finished, Octave also supported; SOFA properties stored to csv files (09.07.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.


function DirectivityGeneral(SOFAfile)
% for DEBUG purpose run the following code:
% DirectivityGeneral('[NEXTCLOUD]\SONICOM\WP5\Ecosystem\Development\Test files\Test Services\5 sofa-directivity-polar.sofa')

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="DirectivityGeneral.log";
fid = fopen(logfile, "w");
s = pwd;
disp(["pwd = " s]);

%% Prologue: (un)comment here if you want to:
% clc; % clean-up first
close all; % clean-up first
tic; % timer
SOFAstart; % remove this optionally
% warning('off','SOFA:upgrade');
% warning('off','SOFA:load');
% warning('off','SOFA:save');
% warning('off','SOFA:save:API');
warning('off'); %jw:note disable all warnings

%jw:note Check if function called with parameter. If not, use command line parameter^M
if(exist("SOFAfile"))
    if(length(SOFAfile)==0)
        disp('The SOFA file name SOFAfile is empty');
    end
else
    % Use command line parameter for SOFAfile
    % disp("SOFAfile does not exist");
    disp(argv);
    arg_list = argv();
    fn = arg_list{1};
    disp(fn);
    SOFAfile = fn;
end
%disp(["SOFAfile = " SOFAfile]);

%% Load SOFA file
%disp(['Loading: ' SOFAfile]);
Obj=SOFAload(SOFAfile);

mySaveSOFAproperties(Obj, SOFAfile);
if isoctave; fputs(fid, ["Successfully saved SOFA details to csv files\n"]); end

if isoctave; fputs(fid, [ "About to plot\n"]); end

%% Plot a few figures
% switch Obj.GLOBAL_SOFAConventions
%     % maybe other directivity cases will follow
%     case 'FreeFieldDirectivityTF';
% if isoctave; fputs(fid, [ "case FreeFieldDirectivityTF\n"]); end
% figure('Name',SOFAfile);
% fputs(fid, [ "just done figure\n"]);


%
%         SOFAplotHRTF(Obj,'MagHorizontal','noconversion2ir');
%         % SOFAplotGeometry(Obj);
%         if isoctave; fputs(fid, [ "just done SOFAplotGeometry\n"]); end


%% FILLED CONTOUR PLOT
figure('Name',SOFAfile);
% fputs(fid, [ "just done figure\n"]);
SOFAplotHRTF(Obj,'MagHorizontal','noconversion2ir');
% SOFAplotHRTF(Obj,'MagHorizontal');

% ask Piotr...:
% ----> The problem is that there is no azimuth data.
% SourcePosition is [0 0 0], ReceiverPosition make no sense, other
% positions are [0 0 0]
%       these are the data to be plotted:
% XData: [20 25 31.5000 40 50 63 80 100 125 160 200 250 315 400 500 630 800 1000 1250 1600 2000 … ] (1×31 double)
% YData: 0
% ZData: -50
% CData: -50



%% POLAR PLOTS

% Use TF data if available
receiver = 1;  % Left ear
%freqs = [2000, 4000];  % Frequencies to plot (Hz)
freqs = [31.5, 63, 125, 250, 500, 1000, 2000, 4000, 8000, 16000];  % Frequencies to plot (Hz)

% Check if TF data exists
if isfield(Obj.Data, 'Real') && isfield(Obj.Data, 'Imag')
    TF = double(squeeze(Obj.Data.Real(:, receiver, :) + 1i * Obj.Data.Imag(:, receiver, :)));  % MN
    freq = double(Obj.N);  % Frequency axis from file

    % @Piotr: I think the problem is that the source position is Obj.SourcePosition

    pos = Obj.ReceiverPosition(receiver,:);
    % pos = Obj.SourcePosition; ' always 0
    azi = mod(pos(:,1), 360);  % Azimuth in degrees (wrapped to 0–360)
    theta = deg2rad(azi);      % Convert to radians for polarplot

    for f = freqs
        [~, idxF] = min(abs(freq - f));
        mag = 20 * log10(abs(TF(:, idxF)));

        figure;
        if isoctave
            polar(repmat(theta, size(mag)), mag); % Piotr: empty figures are created...
        else
            % polarplot(theta, mag, 'LineWidth', 30); % Piotr: empty figures are created...
            polarplot(repmat(theta, size(mag)), mag); % Piotr: empty figures are created...
        end
        title(sprintf('HRTF magnitude at %d Hz', round(freq(idxF))));
        set(gcf, 'Name', sprintf('HRTF_%d', f));
        if isoctave; fputs(fid, [ "renamed figure\n"]); end
        % Save figure as PNG
        filename = sprintf('%s_%d', SOFAfile, round(freq(idxF)));
        % print('-dpng', '-r300', filename);
        print ('-dpng', "-r600", [filename '.png'])
        disp(['Saved figure: ' filename]);
        if isoctave; fputs(fid, [ "just printed " filename ".png\n"]); end
    end
else
    error('No valid data.');
end
% end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fputs(fid, [ "DONE\n"]); end
if isoctave; fclose(fid); end;
toc; % timer


end



function mySaveSOFAproperties(Obj, SOFAfile)
%% get dimensions definitions
% dataDim = {}; % Initialize cell array
isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;
field_namesDim = fieldnames(Obj.API);
for i = 1:length(field_namesDim)
    field_name = field_namesDim{i};

    if ~startsWith(field_name, 'Dimensions')
        switch field_name
            case 'R'
                % description = 'Number of receivers or harmonic coefficients describing receivers';
                dimR = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'E'
                % description = 'Number of emitters or harmonic coefficients describing emitters';
                dimE = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'M'
                % description = 'Number of measurements';
                dimM = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'N'
                % description = 'Number of data samples describing one measurement';
                dimN = get_sofa_field(Obj.API, field_name, 'unknown');
            case 'S'
                % description = 'Number of characters in a string';
                dimS = get_sofa_field(Obj.API, field_name, 'unknown');

            otherwise
                % description = '';
        end

        % if ~isempty(description)
        %     dataDim{end+1, 1} = [description];
        %     % data{end+1, 1} = ['Dimension ' description];
        %     dataDim{end, 2} = get_sofa_field(Obj.API, field_name, 'unknown');
        % end
    end
end
% if isoctave; fputs(log_fid, "Collected dimensions definitions from SOFA file.\n"); end


%% Write dimensions data to CSV using Octave's file I/O
header = {'SOFA Conventions', 'R', 'E', 'M', 'N', 'S'}; % Define header
dataDim = {[Obj.GLOBAL_SOFAConventions ' ' Obj.GLOBAL_SOFAConventionsVersion], dimR, dimE, dimM, dimN, dimS}; % Define data
output_filename = [SOFAfile, '_dim.csv']; % Construct output filename
delimiter = ';'; % Define delimiter
% if isoctave; fputs(log_fid, ["Attempting to write CSV: " output_filename "\n"]); end
csv_fid = fopen(output_filename, 'w'); % Open file for writing
if csv_fid < 0
    % if isoctave; fputs(log_fid, ["Error opening output CSV file: " output_filename "\n"]); fclose(log_fid); end
    error('Cannot open output file for writing: %s', output_filename);
end
try
    % Write header
    % fprintf(csv_fid, '%s%s%s%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});
    fprintf(csv_fid, '%s%s%s%s%s%s%s%s%s%s%s\n', header{1}, delimiter, header{2}, delimiter, header{3}, delimiter, header{4}, delimiter, header{5}, delimiter, header{6});

    % if isoctave; fputs(log_fid, "CSV header written.\n"); end
    fprintf(csv_fid, '%s%s%s%s%s%s%s%s%s%s%s\n', dataDim{1}, delimiter, dataDim{2}, delimiter, dataDim{3}, delimiter, dataDim{4}, delimiter, dataDim{5}, delimiter, dataDim{6});

    % if isoctave; fputs(log_fid, ["Data rows written to CSV.\n"]); end
catch ME
    fclose(csv_fid); % Close file even if error occurs during writing
    % if isoctave; fputs(log_fid, ["Error writing CSV data: " ME.message "\n"]); fclose(log_fid); end
    error('Error writing data to CSV file "%s\": %s', output_filename, ME.message);
end
fclose(csv_fid); % Close the output file successfully
% if isoctave; fputs(log_fid, ["Successfully saved SOFA details to " output_filename "\n"]); end
disp(['Successfully saved SOFA details to ' output_filename]);

%% Iterate over all fields in the SOFA object
dataProp = {}; % Initialize cell array
field_namesProp = fieldnames(Obj);
for i = 1:length(field_namesProp)
    field_name = field_namesProp{i};
    % Check if the field starts with 'GLOBAL_'
    if startsWith(field_name, 'GLOBAL_')
        dataProp{end+1, 1} = field_name(8:end);  % extractAfter is not supported in Octave
        dataProp{end, 2} = get_sofa_field(Obj, field_name, 'unknown');
    end
end
% if isoctave; fputs(log_fid, "Collected GLOBAL data from SOFA file.\n"); end

%% Write properties data to CSV using Octave's file I/O
header = {'Name', 'Value'}; % Define header
output_filename = [SOFAfile, '_prop.csv']; % Construct output filename
% delimiter = ';'; % Define delimiter
% if isoctave; fputs(log_fid, ["Attempting to write CSV: " output_filename "\n"]); end
csv_fid = fopen(output_filename, 'w'); % Open file for writing
if csv_fid < 0
    % if isoctave; fputs(log_fid, ["Error opening output CSV file: " output_filename "\n"]); fclose(log_fid); end
    error('Cannot open output file for writing: %s', output_filename);
end
try
    % Write header
    fprintf(csv_fid, '%s%s%s\n', header{1}, delimiter, header{2});
    % if isoctave; fputs(log_fid, "CSV header written.\n"); end
    % Write data rows
    for i = 1:size(dataProp, 1)
        % Ensure both elements are strings before writing
        name_str = dataProp{i, 1};
        value_str = dataProp{i, 2};
        if ~ischar(name_str); name_str = num2str(name_str); end % Convert if not char
        if ~ischar(value_str); value_str = num2str(value_str); end % Convert if not char
        % Robust CSV quoting and newline removal:
        name_str = ['"', strrep(strrep(name_str, char(10), ' '), char(13), ' '), '"']; % Quote, replace LF and CR with space
        value_str = ['"', strrep(strrep(value_str, char(10), ' '), char(13), ' '), '"']; % Quote, replace LF and CR with space
        fprintf(csv_fid, '%s%s%s\n', name_str, delimiter, value_str);
    end
    % if isoctave; fputs(log_fid, ["Data rows written to CSV.\n"]); end
catch ME
    fclose(csv_fid); % Close file even if error occurs during writing
    % if isoctave; fputs(log_fid, ["Error writing CSV data: " ME.message "\n"]); fclose(log_fid); end
    error('Error writing data to CSV file "%s\": %s', output_filename, ME.message);
end
fclose(csv_fid); % Close the output file successfully
% if isoctave; fputs(log_fid, ["Successfully saved SOFA details to " output_filename "\n"]); end
disp(['Successfully saved SOFA details to ' output_filename]);

end


%% Helper function to safely get fields
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



function [M,meta,h]=SOFAplotHRTF(Obj,type,varargin)


% for backward compatibility (type as position-dependent input parameter)
if nargin == 3 && ischar(type) && isscalar(varargin{1})
    %   varargin = flipud(varargin(:));
    R = varargin{1};
    flags.do_normalization=1;
    dir=[0,0];
    color='b';
    thr=2;
    offset=0;
    noisefloor=-50;
    %     convert=1; more comples differing below:

    if exist('OCTAVE_VERSION','builtin')
        % We're in Octave
        if ismember(type,{'MagHorizontal','MagMedian','MagSpectrum','MagSagittal'}) && ismember(lower(Obj.GLOBAL_SOFAConventions),{'freefielddirectivitytf','generaltf','simplefreefieldhrtf'})
            % In Octave 'contains' is not available, thus, the list has to be extended manually
            do_conversion2ir = 0;
        else
            do_conversion2ir = 1;
        end
    else
        % We're in Matlab
        if contains(lower(type),'mag') && ismember(lower(Obj.GLOBAL_SOFAConventions),{'freefielddirectivitytf','generaltf','simplefreefieldhrtf'})
            % frequency domain input data only
            do_conversion2ir = 0;
        else
            do_conversion2ir = 1;
        end
    end

else
    definput.keyvals.receiver=1;
    definput.keyvals.dir=[0,0];
    definput.keyvals.thr=2;
    definput.keyvals.offset=0;
    definput.keyvals.floor=-50;
    definput.flags.color={'b','r','k','y','g','c','m'};
    definput.flags.normalization={'normalization','nonormalization'};
    definput.flags.conversion2ir={'conversion2ir','noconversion2ir'};
    definput.flags.itdestimator = {'Threshold','Cen_e2','MaxIACCr', 'MaxIACCe', 'CenIACCr', 'CenIACCe', 'CenIACC2e', 'PhminXcor','IRGD'};
    argin=varargin;
    %     for ii=1:length(argin)
    %         if ischar(argin{ii}), argin{ii}=lower(argin{ii}); end
    %     end
    [flags,kv] = SOFAarghelper({'receiver','dir','thr','offset','floor'},definput,argin);
    R = kv.receiver;
    dir = kv.dir;
    thr=kv.thr;
    color = flags.color;
    offset = kv.offset;
    noisefloor=kv.floor;
    do_conversion2ir=flags.do_conversion2ir; % force convertion to TF (or not)

end

meta=[];

if do_conversion2ir == 1
    %% Convert data to FIR
    Obj=SOFAconvertConventions(Obj);
    fs=Obj.Data.SamplingRate;

    %% check if receiver selection is possible
    if R > size(Obj.Data.IR,2)
        error(['Choosen receiver out of range. Only ', num2str(size(Obj.Data.IR,2)), ' receivers recorded.'])
    end
    titlepostfix=' (converted to IR)';
else
    %% check if receiver selection is possible
    if R > size(Obj.Data.Real,2)
        error(['Choosen receiver out of range. Only ', num2str(size(Obj.Data.Real,2)), ' receivers recorded.'])
    end
    titlepostfix='';
end
if isfield(Obj, 'GLOBAL_Title') && isempty(Obj.GLOBAL_Title) == 0
    titleprefix = [Obj.GLOBAL_Title ': '];
else
    titleprefix = '';
end


%% Convert to spherical if cartesian
if strcmp(Obj.SourcePosition_Type,'cartesian')
    % %     Obj2=Obj; % compare to old method (Obj2)
    for ii=1:min(Obj.API.M,size(Obj.SourcePosition,1))
        [Obj.SourcePosition(ii,1),Obj.SourcePosition(ii,2),Obj.SourcePosition(ii,3)]=cart2sph(Obj.SourcePosition(ii,1),Obj.SourcePosition(ii,2),Obj.SourcePosition(ii,3));
        Obj.SourcePosition(ii,2)=rad2deg(Obj.SourcePosition(ii,2));
        Obj.SourcePosition(ii,1)=rad2deg(Obj.SourcePosition(ii,1));
        Obj.SourcePosition(ii,1)=mywrapTo180(Obj.SourcePosition(ii,1));
    end
    Obj.SourcePosition_Type='spherical';
    Obj.SourcePosition_Units='degrees,degrees,metre';
end

%% Plot according to the type
switch lower(type)
    % Energy-time curve (ETC) in the horizontal plane
    case 'etchorizontal'
        Obj=SOFAexpand(Obj,'Data.Delay');
        hM=double(squeeze(Obj.Data.IR(:,R,:)));
        pos=Obj.SourcePosition;
        pos(pos(:,1)>180,1)=pos(pos(:,1)>180,1)-360;
        idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
        M=(20*log10(abs(hM(idx,:))));
        pos=pos(idx,:);
        del=round(Obj.Data.Delay(idx,R));
        meta.idx=idx;
        M2=noisefloor*ones(size(M)+[0 max(del)]);
        for ii=1:size(M,1)
            M2(ii,del(ii)+(1:Obj.API.N))=M(ii,:);
        end
        [azi,i]=sort(pos(:,1));
        M=M2(i,:);
        if flags.do_normalization
            M=M-max(max(M));
        end
        M(M<=noisefloor)=noisefloor;
        meta.time = 0:1/fs*1000:(size(M,2)-1)/fs*1000;
        meta.azi = azi;
        h=surface(meta.time,azi,M(:,:));
        set(gca,'FontName','Arial','FontSize',10);
        set(gca, 'TickLength', [0.02 0.05]);
        set(gca,'LineWidth',1);
        cmap=colormap(hot);
        cmap=flipud(cmap);
        shading flat
        colormap(cmap);
        box on;
        colorbar;
        xlabel('Time (ms)');
        ylabel('Azimuth (deg)');
        title([titleprefix 'receiver: ' num2str(R)],'Interpreter','none');

        % Magnitude spectrum in the horizontal plane
    case 'maghorizontal'
        pos=Obj.SourcePosition;   % copy pos to temp. variable
        pos(pos(:,1)>180,1)=pos(pos(:,1)>180,1)-360; % find horizontal plane
        idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr)); % find indices
        pos=pos(idx,:); % truncate pos
        meta.idx=idx;
        if do_conversion2ir == 1  % converted
            hM=double(squeeze(Obj.Data.IR(:,R,:)));
            M=(20*log10(abs(fft(hM(idx,:)')')));
            M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
            if flags.do_normalization
                M=M-max(max(M));
            end

            M(M<noisefloor)=noisefloor;
            [azi,i]=sort(pos(:,1));
            M=M(i,:);
            meta.freq = 0:fs/size(hM,2):(size(M,2)-1)*fs/size(hM,2);
            meta.azi = azi;
            %         figure;
            h=surface(meta.freq,azi,M(:,:));

        else
            M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
            if flags.do_normalization
                M=M-max(max(M));
            end
            M(M<noisefloor)=noisefloor;
            [azi,i]=sort(pos(:,1));
            M=M(i,:);
            %         figure;


            %% this is working in Matlab but not in Octave
            % h=surface(Obj.N',azi,M);

            %% this is working in Octave as well, variables need to be expanded:
            X = azi;                % scalar or vector
            Y = Obj.N(:);           % column vector (31x1)
            Z = M * ones(length(Y), length(X));  % [31 x 1] if azi is scalar

            h = surface(X, Y, Z);   % works now in Octave as well



        end
        shading flat
        xlabel('Frequency (Hz)');
        ylabel('Azimuth (deg)');
        title([titleprefix 'receiver: ' num2str(R) titlepostfix],'Interpreter','none');

        % Magnitude spectrum in the median plane
    case 'magmedian'
        azi=0;
        pos=Obj.SourcePosition;
        idx0=find(abs(pos(:,1))>90);
        pos(idx0,2)=180-pos(idx0,2);
        pos(idx0,1)=180-pos(idx0,1);
        idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
        pos=pos(idx,:);
        meta.idx=idx; % PM: TODO: check if the correct index

        if do_conversion2ir == 1  % converted

            hM=double(squeeze(Obj.Data.IR(:,R,:)));
            M=(20*log10(abs(fft(hM(idx,:)')')));
            M=M(:,1:floor(size(M,2)/2));  % only positive frequencies

            if flags.do_normalization
                M=M-max(max(M));
            end
            M(M<noisefloor)=noisefloor;
            [ele,i]=sort(pos(:,2));
            M=M(i,:);
            meta.freq = 0:fs/size(hM,2):(size(M,2)-1)*fs/size(hM,2);
            meta.ele = ele;

            h=surface(meta.freq,ele,M(:,:));
        else
            M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
            if flags.do_normalization
                M=M-max(max(M)); % normalize
            end
            M(M<noisefloor)=noisefloor;
            [ele,i]=sort(pos(:,2));
            M=M(i,:);
            %         figure;
            h=surface(Obj.N',ele,M);

        end
        shading flat
        xlabel('Frequency (Hz)');
        ylabel('Elevation (deg)');
        title([titleprefix 'receiver: ' num2str(R) titlepostfix],'Interpreter','none');

        % Magnitude spectrum in the median plane
    case 'magsagittal'

        [lat,pol]=sph2hor(Obj.SourcePosition(:,1),Obj.SourcePosition(:,2));
        pos=[lat pol];
        idx=find(pos(:,1)<(offset+thr) & pos(:,1)>(offset-thr));
        pos=pos(idx,:);
        meta.idx=idx;

        if do_conversion2ir == 1  % converted

            hM=double(squeeze(Obj.Data.IR(:,R,:)));
            M=(20*log10(abs(fft(hM(idx,:)')')));
            M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
            if flags.do_normalization
                M=M-max(max(M));
            end
            M(M<noisefloor)=noisefloor;
            [ele,i]=sort(pos(:,2));
            M=M(i,:);
            meta.freq = 0:fs/size(hM,2):(size(M,2)-1)*fs/size(hM,2);
            meta.ele = ele;
            h=surface(meta.freq,ele,M(:,:));
        else
            M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
            if flags.do_normalization
                M=M-max(max(M));
            end
            M(M<noisefloor)=noisefloor;
            [ele,i]=sort(pos(:,2));
            M=M(i,:);
            h=surface(Obj.N',ele,M(:,:));

        end
        shading flat
        xlabel('Frequency (Hz)');
        ylabel('Polar angle (deg)');
        title([titleprefix 'receiver: ' num2str(R) '; Lateral angle: ' num2str(offset) 'deg' titlepostfix],'Interpreter','none');


        % ETC in the median plane
    case 'etcmedian'
        %     noisefloor=-50;
        azi=0;
        Obj=SOFAexpand(Obj,'Data.Delay');
        hM=double(squeeze(Obj.Data.IR(:,R,:)));
        pos=Obj.SourcePosition;
        idx0=find(abs(pos(:,1))>90);
        pos(idx0,2)=180-pos(idx0,2);
        pos(idx0,1)=180-pos(idx0,1);
        idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
        meta.idx=idx; % PM: TODO: Check if the correct index
        M=(20*log10(abs(hM(idx,:))));
        pos=pos(idx,:);
        del=round(Obj.Data.Delay(idx,R));
        M2=zeros(size(M)+[0 max(del)]);
        for ii=1:size(M,1)
            M2(ii,del(ii)+(1:Obj.API.N))=M(ii,:);
        end
        if flags.do_normalization
            M=M2-max(max(M2));
        else
            M = M2;
        end
        M(M<noisefloor)=noisefloor;
        [ele,i]=sort(pos(:,2));
        M=M(i,:);
        meta.time = 0:1/fs*1000:(size(M,2)-1)/fs*1000;
        meta.ele = ele;
        h=surface(meta.time,ele,M(:,:));
        set(gca,'FontName','Arial','FontSize',10);
        set(gca, 'TickLength', [0.02 0.05]);
        set(gca,'LineWidth',1);
        cmap=colormap(hot);
        cmap=flipud(cmap);
        shading flat
        colormap(cmap);
        box on;
        colorbar;
        xlabel('Time (ms)');
        ylabel('Elevation (deg)');
        title([titleprefix 'receiver: ' num2str(R)],'Interpreter','none');

    case 'magspectrum'
        pos=round(Obj.SourcePosition*10)/10;
        switch size(dir,2)
            case 1
                aziPos = pos(:,1);
                aziDir=dir(:,1);
                aziComp = intersect(aziPos,aziDir,'rows');
                idx= find(ismember(aziPos,aziComp,'rows'));
            case 2
                aziPos = pos(:,1);
                aziDir=dir(:,1);
                elePos = pos(:,2);
                eleDir=dir(:,2);
                aziComp = intersect(aziPos,aziDir,'rows');
                eleComp = intersect(elePos,eleDir,'rows');
                idx=find(ismember(aziPos,aziComp,'rows') & ...
                    ismember(elePos,eleComp,'rows'));
            otherwise
                aziPos = pos(:,1);
                aziDir=dir(:,1);
                elePos = pos(:,2);
                eleDir=dir(:,2);
                rPos = pos(:,3);
                rDir=dir(:,3);
                aziComp = intersect(aziPos,aziDir,'rows');
                eleComp = intersect(elePos,eleDir,'rows');
                rComp = intersect(rPos,rDir,'rows');
                idx=find(ismember(aziPos,aziComp,'rows') & ...
                    ismember(elePos,eleComp,'rows') & ismember(rPos,rComp,'rows'));
        end
        if isempty(idx), error('Position not found'); end
        meta.idx=idx;

        if do_conversion2ir == 1  % convert
            IR=squeeze(Obj.Data.IR(idx,R,:));
            if length(idx) > 1
                M=20*log10(abs(fft(IR')))';
                M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
                h=plot(0:fs/2/size(M,2):(size(M,2)-1)*fs/2/size(M,2),M);
                for ii=1:length(idx)
                    labels{ii}=['#' num2str(idx(ii)) ': (' num2str(pos(idx(ii),1)) ', ' num2str(pos(idx(ii),2)) ')'];
                end
                legend(labels);
            else % only one curve
                hM=20*log10(abs(fft(IR)));
                M=hM(1:floor(length(hM)/2));
                hold on;
                h=plot(0:fs/2/length(M):(length(M)-1)*fs/2/length(M),M,color,...
                    'DisplayName',['#' num2str(idx) ': (' num2str(pos(idx,1)) ', ' num2str(pos(idx,2)) ')']);
                legend;
            end
            xlim([0 fs/2]);
            titlepostfix=' (converted to IR)';
        else

            M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));

            if length(idx) > 1
                h=plot(Obj.N',M);
                for ii=1:length(idx)
                    labels{ii}=['#' num2str(idx(ii)) ': (' num2str(pos(idx(ii),1)) ', ' num2str(pos(idx(ii),2)) ')'];
                end
                legend(labels);
            else
                hold on;
                h=plot(Obj.N',M,color,...
                    'DisplayName',['#' num2str(idx) ': (' num2str(pos(idx,1)) ', ' num2str(pos(idx,2)) ')']);
                legend;
            end
            titlepostfix='';
        end
        ylabel('Magnitude (dB)');
        xlabel('Frequency (Hz)');
        ylim([max(max(M))+noisefloor-10 max(max(M))+10]);
        title([titleprefix 'receiver: ' num2str(R) titlepostfix],'Interpreter','none');

        % Interaural time delay in the horizontal plane
    case 'itdhorizontal'

        if exist('OCTAVE_VERSION','builtin')
            warning('Command ''polarplot'' not supported by Octave (yet)!')
        else
            [itd, ~] = SOFAcalculateITD(Obj, 'time',flags.itdestimator);
            pos = Obj.SourcePosition;
            idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
            itd = itd(idx);
            meta.idx=idx;
            [pos, idx_sort] = sort(pos(idx,1));
            itd = itd(idx_sort);
            angles = deg2rad(pos);
            %figure('Renderer', 'painters', 'Position', [10 10 700 450]);
            polarplot(angles, abs(itd), 'linewidth', 1.2);
            ax = gca;
            ax.ThetaDir = 'counterclockwise';
            ax.ThetaZeroLocation = 'top';
            rticks([max(itd)*2/3, max(itd)]);
            rticklabels({[num2str(round(max(itd)*2/3*1e6,1)) ' ' char(181) 's'],...
                [num2str(round(max(itd)*1e6,1)) ' ' char(181) 's']});
            thetaticks(0:30:330)
            thetaticklabels({'0°', '30°', '60°', '90°', '120°', '150°', '180°', ...
                '210°', '240°','270°', '300°', '330°'});
            grid on;
        end

    otherwise
        error([type , ' no supported plotting type.'])
end

end

% function f=myifftreal(c,N) % thanks goto the LTFAT <http://ltfat.sf.net>
%     if rem(N,2)==0
%       f=[c; flipud(conj(c(2:end-1,:)))];
%     else
%       f=[c; flipud(conj(c(2:end,:)))];
%     end
%     f=real(ifft(f,N,1));
% end

function newangle = mywrapTo180(angle)
% transfer to range -180:180
newangle = mod(angle+360, 360);
if newangle > 180
    newangle = newangle-360;
end
end

