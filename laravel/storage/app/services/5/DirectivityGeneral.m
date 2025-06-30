%DirectivityGeneral - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: support of Directivity SOFA files implemented (15.04.2025)
% #Author: Michael Mihocic: conventions restriction removed (03.06.2025)
% #Author: Michael Mihocic: file renamed; attempting to create new figures, still work in progress... (27.06.2025)
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
        polarplot(theta, mag, 'LineWidth', 30); % Piotr: empty figures are created...
        title(sprintf('HRTF magnitude at %d Hz', round(freq(idxF))));
        set(gcf, 'Name', sprintf('HRTF_%d', f));
        if isoctave; fputs(fid, [ "renamed figure\n"]); end
        % Save figure as PNG
        filename = sprintf('%s_%d', SOFAfile, round(freq(idxF)));
        % print('-dpng', '-r300', filename);
        print ("-r600", [filename '.png'])
        disp(['Saved figure: ' filename]);
        if isoctave; fputs(fid, [ "just printed " filename "_1.png\n"]); end
    end
else
    error('No valid data.');
end







% end


%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer
