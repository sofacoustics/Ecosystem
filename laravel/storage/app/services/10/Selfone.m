%SelfoneIR - Function to load SOFA files, create and save visualizing x figures

% #Author: Flora Feldner: finalized script and modified for Octave support (18.12.2025)
% #Author: Flora Feldner: added fixes and meeting feedback (08.12.2025)
% #Author: Flora Feldner: rewrote SelfoneIR based on HRTFGeneral (01.12.2025)
% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: minor fixes when creating figures (03.06.2025)
% #Author: Michael Mihocic: creating more figures; using (enhanced) mySOFAplotHRTF instead of SOFA function; ITD figures created (also working in Octave) (23.06.2025)
% #Author: Michael Mihocic: creating more figures: Geometry plotted; script ready to create 8 figures (26.06.2025)
% #Author: Michael Mihocic: several updates and improvements; file renamed from HRIR3.m to HRTFGeneral.m (03.07.2025)
% #Author: Michael Mihocic: create csv files with properties (09.07.2025)
% #Author: Michael Mihocic: support for convention SimpleFreeFieldHRTF added; bug fixed when running in Matlab (18.09.2025)
% #Author: Michael Mihocic: mySOFAplotHRTF for case 'itdhorizontal' updated to compensate Obj.Data.Delay (27.10.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function Selfone(SOFAfile)

	addpath('../shared'); % add the path to shared functions
	logfile = "SelfoneIR.log";

	fid = fopen(logfile, "w");
	s = pwd;
	disp(["pwd = " s]);

	%% Prologue: (un)comment here if you want to:
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
        disp(argv);
        arg_list = argv();
        fn = arg_list{1};
        disp(fn);
        SOFAfile = fn;
	end

	%% Load SOFA file
	Obj = SOFAload(SOFAfile);

	SaveSOFAproperties(Obj, SOFAfile);
    fputs(fid, ["Processing " SOFAfile "\n"]);
	fputs(fid, ["Saved SOFA details to csv files.\n\n"]);

    %% Plot magnitude spectrum
    fputs(fid, ["Plotting magnitude spectrum...\n"]);

    % Effect of M: R={'F3','in-ear'} and E='12'
    fig = figure('Name', SOFAfile);
    chosen_m = 1:Obj.API.M;
    chosen_e = 12;
    chosen_r = [1 4];
    mySOFAplotIRFreq(Obj, 'chosen_m', chosen_m, 'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 2, 'xscale', 'lin');
    filename = [SOFAfile '_spectrum_E=12_R=1_linX.png'];
    set(fig, "units", "pixels");
    set(fig, "position", [100 100 1600 800]);  % [x y width height]
    h = findall(gcf, "-property", "linewidth");
    set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
    print ('-dpng', "-r300","tight", filename);
    fputs(fid, [ "Printed " filename "\n"]);
    close all

    fig = figure('Name', SOFAfile);
    mySOFAplotIRFreq(Obj, 'chosen_m', chosen_m, 'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 2, 'xscale', 'log');
    filename = [SOFAfile '_spectrum_E=12_R=1_logX.png'];
    set(fig, "units", "pixels");
    set(fig, "position", [100 100 1600 800]);  % [x y width height]
    h = findall(gcf, "-property", "linewidth");
    set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
    print ('-dpng', "-r300","tight", filename);
    fputs(fid, [ "Printed " filename "\n\n"]);
    close all

    % Effect of R: M=1, E varies
    for e = 1:Obj.API.E
        fig = figure('Name', SOFAfile);
        chosen_m = 1;
        chosen_e = e;
        chosen_r = 1:Obj.API.R;
        mySOFAplotIRFreq(Obj, 'chosen_m', chosen_m, 'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 0, 'xscale', 'lin');
        filename = [SOFAfile '_spectrum_M=1_E=' ...
                    num2str(chosen_e) '_linX.png'];
        set(fig, "units", "pixels");
        set(fig, "position", [100 100 1600 800]);  % [x y width height]
        h = findall(gcf, "-property", "linewidth");
        set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
        print('-dpng', "-r300", "tight", filename);
        fputs(fid, [ "Printed " filename "\n"]);
        close all

        fig = figure('Name', SOFAfile);
        mySOFAplotIRFreq(Obj, 'chosen_m', chosen_m, 'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 0, 'xscale', 'log');
        filename = [SOFAfile '_spectrum_M=1_E=' ...
                    num2str(chosen_e) '_logX.png'];
        set(fig, "units", "pixels");
        set(fig, "position", [100 100 1600 800]);  % [x y width height]
        h = findall(gcf, "-property", "linewidth");
        set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
        print('-dpng', "-r300", "tight", filename);
        fputs(fid, [ "Printed " filename "\n"]);
        close all
    end

    fputs(fid, "\n");

    % Effect of E: M=1, R varies
    for r = 1:Obj.API.R
        fig = figure('Name', SOFAfile);
        chosen_m = 1;
        chosen_e = 1:Obj.API.E;
        chosen_r = r;
        mySOFAplotIRFreq(Obj,'chosen_m', chosen_m,'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 0, 'xscale', 'lin');
        filename = [SOFAfile '_spectrum_M=1_R=' ...
                    num2str(chosen_r) '_linX.png'];

        set(fig, "units", "pixels");
        set(fig, "position", [100 100 1600 800]);  % [x y width height]
        h = findall(gcf, "-property", "linewidth");
        set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
        print('-dpng', "-r300","tight", filename);
        fputs(fid, [ "Printed " filename "\n"]);
        close all

        fig = figure('Name', SOFAfile);
        mySOFAplotIRFreq(Obj,'chosen_m', chosen_m,'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 0, 'xscale', 'log');
        filename = [SOFAfile '_spectrum_M=1_R=' ...
                    num2str(chosen_r) '_logX.png'];
        set(fig, "units", "pixels");
        set(fig, "position", [100 100 1600 800]);  % [x y width height]
        h = findall(gcf, "-property", "linewidth");
        set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
        print('-dpng', "-r300", "tight", filename);
        fputs(fid, [ "Printed " filename "\n"]);
        close all
    end
    fputs(fid, ["Finished execution of SOFAplotIRFreq.\n\n"]);

    %% Change graphics toolkit
    graphics_toolkit gnuplot;
    
	%% Energy, Effect of R: M=1, E varies
	IREnergy = Obj.Data.IR.^2;
    IREnergysum = squeeze(sum(IREnergy, 3));
    IREnergysumdB = 10*log10(IREnergysum/max(IREnergysum(:)));

    fputs(fid, ["Plotting energy distribution...\n"]);
    for chosen_m=1:1
        for chosen_e=1:Obj.API.E
            mySOFAplotGeoEnergy(Obj, IREnergysumdB, chosen_m, chosen_e);
            fig = gcf;
            view(0,0);

            filename = [SOFAfile '_energy_M=1_E=' num2str(chosen_e) '.png'];
            set(fig, "units", "pixels");
            set(fig, "position", [0 0  1080 700]);  % [x y width height]
            h = findall(gcf, "-property", "linewidth");
            set(h, {"linewidth"}, num2cell(8 * cell2mat(get(h, "linewidth"))));
            set(gca, "position", [0.15 0.02 0.63 1]);
            print('-dpng', "-r150", "-tight", filename);
            fputs(fid, [ "Printed " filename "\n"]);
            close all;
        end
    end
    fputs(fid, [ "Finished execution of SOFAplotGeoEnergy.\n\n"]);

    %% Change graphics toolkit
    graphics_toolkit qt;

    %% Geometry
    fputs(fid, ["Plotting geometry...\n"]);
    mySOFAplotGeometry(Obj);
    fig = gcf;
    set(fig, "units", "pixels");
    set(fig, "position", [100 100 1500 1000]);  % [x y width height]
    h = findall(gcf, "-property", "linewidth");
    set(h, {"linewidth"}, num2cell(2*cell2mat(get(h, "linewidth"))));
    set(gca, "position", [0 0.055 0.63 1]);
    print('-dpng', "-r150","-tight", [SOFAfile '_geometry.png']);
    fputs(fid, [ "Printed " SOFAfile "_geometry.png\n"]);
    fputs(fid, ["Finished execution of SOFAplotGeometry.\n\n"]);
    close all;

	%% Epilogue
	disp('DONE');
	fputs(fid, [ "### DONE ###\n\n\n"]);
	fclose(fid);
	toc; % timer

end


function mySOFAplotIRFreq(Obj, varargin)

    definput.keyvals.chosen_r = 1;
    definput.keyvals.chosen_e = 1;
    definput.keyvals.chosen_m = 1;
    definput.keyvals.average_m = 0;
    definput.keyvals.xscale = 'lin';
    argin = varargin;
    for ii = 1:length(argin)
        if ischar(argin{ii})
           argin{ii} = lower(argin{ii}); 
        end
    end
    [flags, kv] = SOFAarghelper({'chosen_r', 'chosen_e', 'chosen_m', 'average_m', 'xscale'}, definput, argin);

    r = kv.chosen_r;
    e = kv.chosen_e;
    m = kv.chosen_m;
    average_m = kv.average_m;

    xscale = kv.xscale;

    r = r(:)';
    e = e(:)';
    m = m(:)';

    IR = permute(Obj.Data.IR, [4 2 1 3]); % E, R, M, N is easier
    selected_data = IR(e, r, m, :);

    n = size(Obj.Data.IR, 3);
    fft_interpolation_factor = 16;
    fs = Obj.Data.SamplingRate;
    freq = 0:fs/(n*fft_interpolation_factor):(floor((n*fft_interpolation_factor)/2)-1) * fs/(n*fft_interpolation_factor);

    if length(m) > 1 && average_m >= 1
        avg_mat = zeros(length(e), length(r), 1, n);
        for e_idx = 1:length(e)
            for r_idx = 1:length(r)
                hM = squeeze(selected_data(e_idx, r_idx, :, :));
                fft_mat = fft(hM, n, 2);
                fft_mat = sum(fft_mat, 1) ./ size(fft_mat, 1);
                avg_mat(e_idx,r_idx, 1, :) = ifft(fft_mat, n, 2);
            end
        end
        if average_m == 1
            selected_data = avg_mat;
            m = 0;
        else % plot both avg and individual m when 2
            m = [0 m];
            selected_data = cat(3, avg_mat, selected_data);
        end
    end

    fsize = 26;
    color_grey = [0.7 0.7 0.7];

    set(gca, 'XMinorTick', 'Off')
    set(gca, 'YMinorTick', 'Off')

    box on;
    hold on;

    xlim([400 40000])
    ylim([-100 -20])

    if strcmp(xscale, 'lin')
        xgridvals = [400 5000:5000:40000];
        xticklabels({'400', '5k', '10k', '15k', '20k', '25k', '30k', '35k', '40k'});
    elseif strcmp(xscale, 'log')
        xgridvals = [400, 1000, 2000, 4000, 6000, 10000, 20000, 40000];
        xticklabels({'400', '1k', '2k', '4k', '6k', '10k', '20k', '40k'});
        set(gca, 'xscale', 'log');
    end

    xticks(xgridvals);

    ygridvals = -100:20:-20;
    yticks(ygridvals);

    xl = xlim;
    for yv = ygridvals
        line(xl, [yv yv], 'color', [0.5 0.5 0.5], 'linestyle', '-', 'linewidth', 0.5);
    end

    yl = ylim;
    for xv = xgridvals
        line([xv xv], yl, 'color', [0.5 0.5 0.5], 'linestyle', '-', 'linewidth', 0.5);
    end

    set(gca, 'fontsize', fsize);
    xlabel('Frequency (Hz)', 'fontsize', fsize);
    ylabel("Magnitude (dB)", 'fontsize', fsize);

    black_thickie = [];
    red_thickie = [];
    color_grey = [0.7 0.7 0.7];

    isfirstplot = 1;
    legendEntries = [];
    legendDescription = [];

    for e_idx = 1:length(e)
        for r_idx = 1:length(r)
            for m_idx = 1:length(m)

                hM = double(squeeze(selected_data(e_idx, r_idx, m_idx, :)));
                M = (20*log10(abs(fft(hM(:), (n*fft_interpolation_factor))')));

                M = M(:, 1:floor(size(M, 2)/2));  % only positive frequencies

                if isscalar(m) && isscalar(r) % legend for effect of R
                    if r(r_idx)==1
                        legendEntries = plot(freq, M, "LineWidth", 1);
                        legendDescription{end+1} = 'In-Ear IRs';
                    else
                        legendEntries = plot(freq, M, "LineWidth", 1);
                        legendDescription{end+1} = 'SIRs';
                    end
                end

                if isscalar(e) && isscalar(m) % plotting over all receivers
                    if r_idx == 1
                        black_thickie = M;
                    else
                        if isfirstplot
                            legendEntries = plot(freq, M, "LineWidth", 1);
                            legendDescription{end+1} = 'SIRs';
                            isfirstplot = 0;
                        else
                            plot(freq, M, "LineWidth", 1);
                        end
                    end
                end

                if isscalar(r) && isscalar(m) % plotting over all emitters
                    plot(freq, M, "LineWidth", 1)
                end

                if ~isscalar(m) % plotting the effect of M
                    if m_idx == 1
                        if r_idx == 1
                            red_thickie = M;
                        else
                            black_thickie = M;
                        end
                    else
                        if isfirstplot
                            legendEntries = plot(freq, M, "LineWidth", 1);
                            legendDescription{end+1} = 'Individual Measurements';
                            isfirstplot = 0;
                        else
                            plot(freq, M, "LineWidth", 1);
                        end
                    end
                end

            end 
        end 
    end 

    % plot thick lines
    if ~isempty(black_thickie)
        if isscalar(m)
            legendEntries(end+1) = plot(freq, black_thickie, "LineWidth", 2, 'Color', [0, 0, 0]);
            legendDescription{end+1} = 'In-Ear IR';
            hl = legend(legendEntries, legendDescription, 'Location', 'South');
            set(hl, 'fontsize', fsize, 'box', 'on');
        else
            legendEntries(end+1) = plot(freq, black_thickie, "LineWidth", 2, 'Color', [0, 0, 0]);
            legendDescription{end+1} = 'Averaged SIRs: Mic. 4(F3)';
        end
    end

    if ~isempty(red_thickie)
        legendEntries(end+1) = plot(freq, red_thickie, "LineWidth", 2, 'Color', [1, 0, 0]);
        legendDescription{end+1} = 'Averaged In-Ear IRs';
    end

    if ~isscalar(m)
        hl = legend(legendEntries, legendDescription, 'Location', 'South');
        set(hl, 'fontsize', fsize, 'box', 'on');
    end

    if isscalar(m) && isscalar(r)
        hl = legend(legendEntries, legendDescription, 'Location', 'South');
        set(hl, 'fontsize', fsize, 'box', 'on');
    end

    hold off;

end


function mySOFAplotGeoEnergy(Obj, IREnergysumdB, chosen_m, chosen_e)

     figure; 
    
    % ReceiverPosition and  EmitterPosition
    RP = SOFAconvertCoordinates(Obj.ReceiverPosition(:,:), Obj.ReceiverPosition_Type, 'cartesian');
    EP = SOFAconvertCoordinates(Obj.EmitterPosition(:,:), Obj.EmitterPosition_Type, 'cartesian');

    %for plotting text offset to the center
    labeloffset = 2;
    fsize = 18;
    msize = 12;
    dotsize = 6000;
    color_grey = [0.7 0.7 0.7];

    labeloffset_x = -3.5;
    labeloffset_z = -2;

    hold on;

    % Plot grid
    for r = [10, 20, 30]
        theta = linspace(0, 2*pi, 200);
        circle = [r*cos(theta)', 36*ones(200,1), r*sin(theta)'];
        plot3(circle(:,1), circle(:,2), circle(:,3), 'b-', ...
                'LineWidth', 0.2, 'color', color_grey);
    end

    for ii=2:size(RP,1)
        r = sqrt(RP(ii, 1)*RP(ii, 1)+RP(ii, 3)*RP(ii, 3));
        linepoint = [0 36 0; RP(ii, 1) 36 RP(ii, 3)];
        norm_linepoint = norm(linepoint(2, :)-linepoint(1, :));
        linepoint = linepoint/norm_linepoint*30;        
        plot3(linepoint(:, 1), linepoint(:, 2), linepoint(:, 3), ...
                'LineWidth', 0.2, 'color', color_grey);
    end

    for ii=1:size(EP,1)
        linepoint = [0 36 0; EP(ii, 1) 36 EP(ii, 3)];
        norm_linepoint = norm(linepoint(2, :)-linepoint(1, :));
        linepoint = linepoint/norm_linepoint*30;
        plot3(linepoint(:, 1), linepoint(:, 2), linepoint(:, 3), 'LineWidth', 0.2, 'color', color_grey);
    end

    % Plot emitters
    % Inactive emitters
    % legendEntries = [];
    % legendEntries(end+1) = plot3(NaN, NaN, NaN, '+', ...
    %     'MarkerSize', msize, 'Color', color_grey);
    for ii=1:size(EP,1)
        plot3(EP(ii, 1), EP(ii, 2), EP(ii, 3), '+', ...
            'MarkerSize', msize, 'Color', color_grey);
    end

    % Active emitter
    % legendEntries(end+1) = plot3(NaN, NaN, NaN, ...
    %     'b+', 'MarkerSize', msize+4, 'LineWidth', 2);
    plot3(EP(chosen_e, 1), EP(chosen_e, 2), EP(chosen_e, 3), ...
        'b+', 'MarkerSize', msize+4, 'LineWidth', 2);
    text(EP(chosen_e, 1)+labeloffset_x, EP(chosen_e, 2), EP(chosen_e, 3)+labeloffset_z, ...
        [num2str(chosen_e) '(' Obj.EmitterLabel{chosen_e} ')'], 'fontsize', fsize-2, 'color', 'b');

    axis equal;
    view(0, 0);

    % legendEntries(end+2) = scatter3(NaN, NaN, NaN, ...
    %     dotsize, IREnergysumdB(chosen_m, 2, chosen_e), 'filled');
    scatter3(RP(2:end, 1), RP(2:end, 2), RP(2:end, 3), dotsize, ...
        IREnergysumdB(chosen_m, 2:end, chosen_e), 'filled');
   
    % Plot energy at receiver positions
    % In-ear microphone
    scatter3(RP(1, 1), RP(1, 2), RP(1, 3), dotsize*14, ...
        IREnergysumdB(chosen_m, 1, chosen_e), 'filled');
    
    % Use white label if energy is larger than -15 dB
    if IREnergysumdB(chosen_m, 1, chosen_e) >= -15
        text(RP(1, 1), RP(1, 2), RP(1, 3), ...
                [num2str(1) '(' Obj.ReceiverLabel{1} ')'], ...
                'Color', 'w', ...
                'FontSize', fsize-2, 'HorizontalAlignment', 'center', ...
                'VerticalAlignment', 'middle');
    else
        text(RP(1, 1), RP(1, 2), RP(1, 3), ...
                [num2str(1) '(' Obj.ReceiverLabel{1} ')'], ...
                'Color', 'k', ...
                'FontSize', fsize-2, 'HorizontalAlignment', 'center', ...
                'VerticalAlignment', 'middle');
    end

    % Selfone receivers
    for ii=2:size(RP,1)  
        if ii==9 || ii==21
            text(RP(ii,1)+labeloffset_x, RP(ii,2), RP(ii,3)-labeloffset_z, ...
                [num2str(ii) '(' Obj.ReceiverLabel{ii} ')'], 'fontsize', fsize-2);
        else
            text(RP(ii,1)+labeloffset_x, RP(ii,2), RP(ii,3)+labeloffset_z, ...
                [num2str(ii) '(' Obj.ReceiverLabel{ii} ')'], 'fontsize', fsize-2);
        end
    end

    hot_map = hot;
    inverted_hot = flipud(hot_map);    
    colormap(inverted_hot); % Optional: Change the color scheme (e.g., 'parula', 'turbo', 'hot')

    box on;

    % %create legend (positioning not working well in Octave)
    % legendDescription = {'Inactive emitter', ...
    %     'Active emitter: E(Label)', ...
    %     'Energy at Receivers: R(Label)'};
    % hl = legend(legendEntries, legendDescription, ...
    %         'Location', 'Eastoutside', 'box', 'off', 'fontsize', fsize);

    % legendDescription = {'Active emitter: E(Label)'};
    % hl = legend(legendEntries(2), legendDescription, ...
    %         'Location', 'North', 'box', 'off', 'fontsize', fsize);

    axisLimits = [-33 33 -10 42 -33 33];
    axis(axisLimits);
    set(gca, 'fontsize', fsize);

    offset = -10;
    text(mean(xlim), 0, min(zlim)+offset, 'x (mm)', 'HorizontalAlignment', 'center', 'fontsize', fsize);
    text(min(xlim)+offset, 0, mean(zlim), 'z (mm)', 'Rotation', 90, 'HorizontalAlignment', 'center', 'fontsize', fsize);

    cb = colorbar();
    caxis([-30 0]);
    set(cb, 'FontSize', fsize, 'Location', 'east');
    ylabel(cb, 'Energy re global maximum (dB)', 'FontSize', fsize, 'Rotation', 270);

end


function mySOFAplotGeometry(Obj)

    figure; 
    
    % Get ReceiverPosition and EmitterPosition
    RP = SOFAconvertCoordinates(Obj.ReceiverPosition(:,:), Obj.ReceiverPosition_Type, 'cartesian');
    EP = SOFAconvertCoordinates(Obj.EmitterPosition(:,:), Obj.EmitterPosition_Type, 'cartesian');

    labeloffset_x = -2.5;
    labeloffset_z = -2;
    fsize = 22;
    msize = 18;
    color_grey = [0.7 0.7 0.7];

    hold on;

    % Plot ReceiverPositon (only for the first ListenerPosition)
    legendEntries = [];
    legendEntries(end+1) = plot3(RP(1, 1), RP(1, 2), RP(1, 3), 'r*', 'MarkerSize', msize);

    for r = [10, 20, 30]
        theta = linspace(0, 2*pi, 200);
        circle = [r*cos(theta)', 36*ones(200,1), r*sin(theta)'];
        plot3(circle(:,1), circle(:,2), circle(:,3), 'b-', ...
                'LineWidth', 0.2, 'color', color_grey);
    end

    for ii=2:size(RP, 1)
        linepoint = [0 36 0; RP(ii, 1) 36 RP(ii, 3)];
        norm_linepoint = norm(linepoint(2, :)-linepoint(1, :));
        linepoint = linepoint/norm_linepoint*30;
        plot3(linepoint(:, 1), linepoint(:, 2), linepoint(:, 3), 'LineWidth', 0.2, 'color', color_grey);
    end

    for ii=1:size(RP,1)
        plot3(RP(ii,1), RP(ii,2), RP(ii,3), 'r*', 'MarkerSize', msize);
        
        if ii==9 || ii==21
            text(RP(ii,1)+labeloffset_x, RP(ii,2), RP(ii,3)-labeloffset_z, ...
                [num2str(ii) '(' Obj.ReceiverLabel{ii} ')'], 'fontsize', fsize);
        else
            text(RP(ii,1)+labeloffset_x, RP(ii,2), RP(ii,3)+labeloffset_z, ...
                [num2str(ii) '(' Obj.ReceiverLabel{ii} ')'], 'fontsize', fsize);
        end

    end

    % Plot EmitterPositions
    legendEntries(end+1) = plot3(EP(1,1), EP(1,2), EP(1,3), 'b+', 'MarkerSize', msize);
    text(EP(1,1)+labeloffset_x, EP(1,2), EP(1,3)+labeloffset_z, [num2str(1) '(' Obj.EmitterLabel{1} ')'], 'fontsize', fsize);

    for ii=1:size(EP, 1)
        linepoint = [0 36 0; EP(ii,1) 36 EP(ii,3)];
        norm_linepoint = norm(linepoint(2,:)-linepoint(1,:));
        linepoint = linepoint/norm_linepoint*30;
        plot3(linepoint(:,1), linepoint(:,2), linepoint(:,3), 'LineWidth', 0.2, 'color', color_grey);
    end

    for ii=1:size(EP,1)
        plot3(EP(ii,1), EP(ii,2), EP(ii,3), 'b+', 'MarkerSize', msize);
        text(EP(ii,1)+labeloffset_x, EP(ii,2), EP(ii,3)+labeloffset_z, ...
            [num2str(ii) '(' Obj.EmitterLabel{ii} ')'], 'fontsize', fsize);
    end

    % create legend
    legendDescription = {'Receivers: R(Label)'};
    legendDescription(end+1) = {'Emitters: E(Label)'};
    hl = legend(legendEntries, legendDescription, ...
            'Location', 'Northeast', ...
            'box', 'on');
    set(hl, 'fontsize', fsize, 'box', 'on');

    xlabel('x (mm)', 'HorizontalAlignment', 'center', 'fontsize', fsize);
    zlabel('z (mm)', 'HorizontalAlignment', 'center', 'fontsize', fsize);

    view(0, 0);
    axis equal;
    axisLimits = [-32 32 -10 42 -33 33];
    axis(axisLimits);
    set(gca, 'fontsize', fsize);

    box on;

end