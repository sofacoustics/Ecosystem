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
	Obj=SOFAload(SOFAfile);

	SaveSOFAproperties(Obj, SOFAfile);
	fputs(fid, ["Successfully saved SOFA details to csv files\n"]);

	fputs(fid, [ "About to plot\n"]);

	%% Plot a few figures
	switch Obj.GLOBAL_SOFAConventions
			% differ cases, depending on SOFA conventions
			case 'GeneralFIR-E'
	graphics_toolkit gnuplot

    %% Freqplots, log
    fputs(fid, [ "About to print Frequency plots,log \n"]);

    % Show Effect of M for R={'F3','inear'} and E='12'

    fig = figure('Name',SOFAfile);
    chosen_m = 1:Obj.API.M;
    chosen_e = 12;
    chosen_r = [1 4];
    mySOFAplotIRFreq(Obj,'chosen_m', chosen_m,'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 2);
    box on;
    title('IR Magnitude Spectrum, E: 12(13), R: in-ear,4(F3), over all M + averaged')
    filename=[SOFAfile '_spectrum_E=12_R=1.png'];
    fputs(fid, [ "About to save: " filename]);

    set(fig, "units", "pixels");
    set(fig, "position", [100 100 1200 800]);  % [x y width height]
    h = findall(gcf, "-property", "linewidth");
    set(h, {"linewidth"}, num2cell(4 * cell2mat(get(h, "linewidth"))));
    print ('-dpng', "-r300","tight", filename);
    fputs(fid, [ "just printed " filename]);

    %close all


    % make E, all R plots with thick in-ear for m=1
    for e=1:Obj.API.E

        fig = figure('Name',SOFAfile);
        chosen_m = 1;
        chosen_e = e;
        chosen_r = 1:Obj.API.R;
        mySOFAplotIRFreq(Obj,'chosen_m', chosen_m,'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 0);
        box on;
        title(['IR Magnitude Spectrum, E: ' num2str(e) ' over all R + in-ear for M: 1'])
        filename=[SOFAfile '_spectrum_M=1_E=' ...
                num2str(chosen_e) '.png'];
        fputs(fid, [ "About to save: " filename]);
            set(fig, "units", "pixels");
            set(fig, "position", [100 100 1200 800]);  % [x y width height]
            h = findall(gcf, "-property", "linewidth");
            set(h, {"linewidth"}, num2cell(4 * cell2mat(get(h, "linewidth"))));
                print ('-dpng', "-r300","tight", filename);
        fputs(fid, [ "just printed " filename]);
        close all

    end

    % make all E, R plots
    for r=1:Obj.API.R
        fig = figure('Name',SOFAfile);
        chosen_m = 1;
        chosen_e = 1:Obj.API.E;
        chosen_r = r;
        mySOFAplotIRFreq(Obj,'chosen_m', chosen_m,'chosen_e', chosen_e, 'chosen_r', chosen_r, 'average_m', 0);
        box on;
        title(['IR Magnitude Spectrum, R: ' num2str(r) ' over all E for M: 1'])
        filename=[SOFAfile '_spectrum_M=1_R=' ...
                num2str(chosen_r) '.png'];
        fputs(fid, [ "About to save: " filename]);

        set(fig, "units", "pixels");
        set(fig, "position", [100 100 1200 800]);  % [x y width height]
        h = findall(gcf, "-property", "linewidth");
        set(h, {"linewidth"}, num2cell(4 * cell2mat(get(h, "linewidth"))));
            print ('-dpng', "-r300","tight", filename);

        fputs(fid, [ "just printed " filename]);
        close all
    end

    %% Geometry
    mySOFAplotGeometry(Obj);
    fig=gcf;
    fputs(fid, [ "just done SOFAplotGeometry\n"]);
    view(0,0);
    fputs(fid, [ "adapted view\n"]);
    %title('Selfone Emitter and Receiver Positions');

    set(fig, "units", "pixels");
    set(fig, "position", [100 100 1100 1100]);  % [x y width height]
    h = findall(gcf, "-property", "linewidth");
    set(h, {"linewidth"}, num2cell(4 * cell2mat(get(h, "linewidth"))));
    set(gca, "looseinset", [0 0 0 0]);
    set(gca, "position", [0.13 0.11 0.775 0.815]);
        print ('-dpng', "-r150","-tight", [SOFAfile '_geometry.png']);

    close all;
    fputs(fid, [ "just printed " SOFAfile "_geometry.png\n"]);

	%% Geometric Energy in dB
    IREnergy = Obj.Data.IR.^2;
    IREnergysum = squeeze(sum(IREnergy,3));
    %IREnergysumdB = 10*log10(IREnergysum./max(IREnergysum,[],"all"));
    IREnergysumdB = 10*log10(IREnergysum./max(max(max(IREnergysum))));
    % energy in db, 8x25x20

    for chosen_m=1:1
        for chosen_e=1:Obj.API.E
            mySOFAplotGeoEnergy(Obj,IREnergysumdB,chosen_m,chosen_e);
            fig=gcf;
            fputs(fid, [ "just done SOFAplotGeoEnergy\n"]);
            box on;
            view(0,0);

            fputs(fid, [ "adapted view\n"]);

            title(['Energy distribution across microphones for M=' ...
                    num2str(chosen_m) ' and E=' num2str(chosen_e)]);
            filename=[SOFAfile '_energy_M=1_E=' num2str(chosen_e) '.png'];
            set(fig, "units", "pixels");
            set(fig, "position", [100 100 1100 900]);  % [x y width height]
            h = findall(gcf, "-property", "linewidth");
            set(h, {"linewidth"}, num2cell(4 * cell2mat(get(h, "linewidth"))));
            set(gca, "looseinset", [0 0 0 0]);
            set(gca, "position", [0.13 0.11 0.775 0.815]);
                print ('-dpng', "-r150","-tight", filename);
            close all;

            fputs(fid, [ "just printed " filename]);
        end
    end

	end %switch case, probably unnecessary


	%% Epilogue: (un)comment if you want to:
	disp('DONE');
	fputs(fid, [ "\n### DONE ###\n"]);
	fclose(fid);
	toc; % timer

end

function own_xline(xval, varargin)
line([xval(:) xval(:)], ylim, varargin{:});
end

function mySOFAplotIRFreq(Obj,varargin)


definput.keyvals.chosen_r=1;
definput.keyvals.chosen_e=1;
definput.keyvals.chosen_m=1;
definput.keyvals.average_m=0;
definput.flags.normalize={'normalize','original'};
argin=varargin;
for ii=1:length(argin)
    if ischar(argin{ii}), argin{ii}=lower(argin{ii}); end
end
[flags,kv] = SOFAarghelper({'chosen_r','chosen_e','chosen_m','average_m'},definput,argin);

r = kv.chosen_r;
e = kv.chosen_e;
m = kv.chosen_m;
average_m = kv.average_m;

flags.do_normalize = flags.normalize;

r = r(:)';
e = e(:)';
m = m(:)';

IR = permute(Obj.Data.IR, [4 2 1 3]); % E,R,M,N is easier
selected_data = IR(e,r,m,:);

n = size(Obj.Data.IR,3);
fft_interpolation_factor = 16;
fs=Obj.Data.SamplingRate;
freq = 0:fs/(n*fft_interpolation_factor):(floor((n*fft_interpolation_factor)/2)-1)*fs/(n*fft_interpolation_factor);


if length(m) > 1 && average_m >= 1
    % do vector average
    avg_mat = zeros(length(e),length(r),1,n);
    for e_idx = 1:length(e)
    for r_idx = 1:length(r)
        hM = squeeze(selected_data(e_idx,r_idx,:,:));
        fft_mat = fft(hM,n,2);
        fft_mat = sum(fft_mat,1) ./ size(fft_mat,1);
        avg_mat(e_idx,r_idx,1,:) = ifft(fft_mat,n,2);
    end
    end
    if average_m == 1
        selected_data = avg_mat;
        m = 0;
    else % plot both avg and individual m when 2
        m = [0 m];
        selected_data = cat(3,avg_mat,selected_data);
    end
end

hold on;

black_thickie = [];
red_thickie = [];

isfirstplot = 1;
legendEntries = [];
legendDescription = [];

for e_idx = 1:length(e)
for r_idx = 1:length(r)
for m_idx = 1:length(m)

hM=double(squeeze(selected_data(e_idx,r_idx,m_idx,:)));
M=(20*log10(abs(fft(hM(:),(n*fft_interpolation_factor))')));

M=M(:,1:floor(size(M,2)/2));  % only positive frequencies

if isscalar(e) && isscalar(m) % were plotting over all receivers
    if r_idx == 1
        black_thickie = M;
    else
        if isfirstplot == 1
            legendEntries = plot(freq,M,"LineWidth",1);
            legendDescription{end+1} = 'Selfone Mics';
            isfirstplot = 0;
        else
            plot(freq,M,"LineWidth",1);
        end
    end
end

if isscalar(r) && isscalar(m) % were plotting over all emitters
        plot(freq,M,"LineWidth",1)
end

if ~isscalar(m) % were plotting the M effect picture
    if m_idx == 1
        if r_idx == 1
            red_thickie = M;
        else
            black_thickie = M;
        end
    else
        if isfirstplot == 1
            legendEntries = plot(freq,M,"LineWidth",1);
            legendDescription{end+1} = 'individual Measurements';
            isfirstplot = 0;
        else
            plot(freq,M,"LineWidth",1);
        end
    end


end


% set(gca, 'XScale', 'log')

end % m loop end
end % r loop end
end % e loop end

%xline([400 2000:2000:36000], 'color', [.8 .8 .8]);
own_xline([400 2000:2000:36000], 'color', [.8 .8 .8]);

% plot thickies
if ~isempty(black_thickie)
    if isscalar(m)
        legendEntries(end+1) = plot(freq,black_thickie,"LineWidth",2,'Color',[0,0,0]);
        legendDescription{end+1} = 'In-Ear Mic';
        legend(legendEntries,legendDescription,'Location','NorthEast');
    else
        legendEntries(end+1) = plot(freq,black_thickie,"LineWidth",2,'Color',[0,0,0]);
        legendDescription{end+1} = 'averaged Mic F3';

    end
end
if ~isempty(red_thickie)
    legendEntries(end+1) = plot(freq,red_thickie,"LineWidth",2,'Color',[1,0,0]);
    legendDescription{end+1} = 'averaged In-Ear Mic';
end

if ~isscalar(m)
    legend(legendEntries,legendDescription,'Location','NorthEast');
end

set(gca,'XMinorTick','Off')
xlim([400 36000])
xtickangle(45)
xticks([400 2000:2000:36000]);

xticklabels({'400','2k','4k','6k','8k','10k','12k','14k','16k','18k','20k','22k','24k','26k','28k','30k','32k','34k','36k'})
ylim([-100 -20])
xlabel('Frequency (Hz)');
ylabel("|fft(IR)| (dB)")

hold off;
end % function end


function mySOFAplotGeometry(Obj)
    figure; hold on;

    legendEntries = [];
    % title(sprintf('%s, %s',Obj.GLOBAL_SOFAConventions,Obj.GLOBAL_RoomType));

    % Get  ReceiverPosition and EmitterPosition

    RP = SOFAconvertCoordinates(Obj.ReceiverPosition(:,:),Obj.ReceiverPosition_Type,'cartesian');


    EP = SOFAconvertCoordinates(Obj.EmitterPosition(:,:),Obj.EmitterPosition_Type,'cartesian');


    labeloffset = 1;
    % Plot ReceiverPositon (this is plotted only for the first ListenerPosition)

    legendEntries(end+1) = plot3(RP(1,1), RP(1,2), RP(1,3),'r*','MarkerSize',8);
    for ii=2:size(RP,1)
        linepoint = [0 36 0; RP(ii,1) 36 RP(ii,3)];
        r = sqrt(RP(ii,1)*RP(ii,1)+RP(ii,3)*RP(ii,3));
        theta = linspace(0, 2*pi, 200);
        circle = [r * cos(theta)'  36*ones(200,1)  r * sin(theta)'];
        plot3(circle(:,1), circle(:,2), circle(:,3), 'b-', 'LineWidth', 0.2,'color', [0.1 0 0]+0.75);
        plot3(linepoint(:,1),linepoint(:,2),linepoint(:,3), 'LineWidth', 0.7, 'color', [0.1 0 0]+0.7);
    end
    for ii=2:size(RP,1)
        plot3(RP(ii,1), RP(ii,2), RP(ii,3),'r*','MarkerSize',8);
        text(RP(ii,1)+labeloffset, RP(ii,2), RP(ii,3),[num2str(ii) '(' Obj.ReceiverLabel{ii} ')']);
    end
    text(RP(1,1)+labeloffset, RP(1,2), RP(1,3),[num2str(1) '(' Obj.ReceiverLabel{1} ')']);


    % Plot EmitterPositions

    % plot Emitters
    legendEntries(end+1) = plot3(EP(1,1), EP(1,2), EP(1,3),'b+','MarkerSize',8);
    text(EP(1,1)+labeloffset, EP(1,2), EP(1,3), [num2str(1) '(' Obj.EmitterLabel{1} ')']);

    for ii=2:size(EP,1)
        linepoint = [0 0 0; EP(ii,:)];
        plot3(linepoint(:,1),linepoint(:,2),linepoint(:,3), 'LineWidth', 0.7, 'color', [0.7 0.7 0.8]);
    end

    for ii=2:size(EP,1)
        plot3(EP(ii,1), EP(ii,2), EP(ii,3),'b+','MarkerSize',8);
        text(EP(ii,1)+labeloffset, EP(ii,2), EP(ii,3),[num2str(ii) '(' Obj.EmitterLabel{ii} ')']);
    end


    % create legend
    legendDescription = {'Receivers R(Label)'};
    legendDescription{end+1} = 'Emitters E(Label)';

    legend(legendEntries,legendDescription,'Location','NorthEast');

    xlabel(['x (' strrep(Obj.ListenerPosition_Units, 'metre', 'mm') ')']);
    ylabel(['y (' strrep(Obj.ListenerPosition_Units, 'metre', 'mm') ')']);
    zlabel(['z (' strrep(Obj.ListenerPosition_Units, 'metre', 'mm') ')']);

    % formatting figures

    % Set fixed aspect ratio
    axis equal;
    % Add a little bit extra space at the axis
    % axisLimits = axis();
    % % paddingSpace = 0.2 * max(abs(axisLimits(:)));
    % paddingSpace = 0;
    % axisLimits([1 3]) = axisLimits([1 3]) - paddingSpace;
    % axisLimits([2 4]) = axisLimits([2 4]) + paddingSpace;
    axisLimits = [-33 33 -10 42 -33 33];
    axis(axisLimits);

    box on;

end

%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
function mySOFAplotGeoEnergy(Obj,IREnergysumdB,chosen_m,chosen_e)
    figure; hold on;

    legendEntries = [];
    % title(sprintf('%s, %s',Obj.GLOBAL_SOFAConventions,Obj.GLOBAL_RoomType));

    % ReceiverPosition and
    % EmitterPosition


    RP = SOFAconvertCoordinates(Obj.ReceiverPosition(:,:),Obj.ReceiverPosition_Type,'cartesian');

    EP = SOFAconvertCoordinates(Obj.EmitterPosition(:,:),Obj.EmitterPosition_Type,'cartesian');

    view(0,0);

    %for plotting text offset to the center
    labeloffset = 2;




    % plot Active Emitter
    legendEntries(end+1) = plot3(EP(chosen_e,1), EP(chosen_e,2), EP(chosen_e,3), ...
        'b+','MarkerSize',8, 'LineWidth', 2);
         plot3(EP(chosen_e,1), EP(chosen_e,2), EP(chosen_e,3), ...
        'b+','MarkerSize',20, 'LineWidth', 2);
    point = [EP(chosen_e,3) -EP(chosen_e,1)]; point = point/norm(point);
    text(EP(chosen_e,1)+labeloffset*point(1),EP(chosen_e,2), EP(chosen_e,3)+labeloffset*point(2), ...
        [num2str(chosen_e) '(' Obj.EmitterLabel{chosen_e} ')'], ...
        'FontSize', 9, 'HorizontalAlignment','center', 'VerticalAlignment','middle');
    % faintly plot the others
    for ii=1:size(EP,1)
        plot3(EP(ii,1), EP(ii,2), EP(ii,3),'+','MarkerSize',8,'Color',[0.6, 0.6, 0.6]);
    end


    for ii=2:size(RP,1)
        r = sqrt(RP(ii,1)*RP(ii,1)+RP(ii,3)*RP(ii,3));
        linepoint = [0 36 0; RP(ii,1) 36 RP(ii,3)];
        theta = linspace(0, 2*pi, 200);
        circle = [r * cos(theta)'  36*ones(200,1)  r * sin(theta)'];
        plot3(circle(:,1), circle(:,2), circle(:,3), 'b-', 'LineWidth', 0.2,'color', [0.1 0 0]+0.75);
        plot3(linepoint(:,1),linepoint(:,2),linepoint(:,3), 'LineWidth', 0.7, 'color', [0.1 0 0]+0.7);
    end

    %for plotting receiver text offset to the center
    labeloffset=4.0;

    legendEntries(end+1) =plot3(0, 40, 0, ...
        'ko','MarkerSize',8, 'LineWidth', 2);% dirty hack for a black legend point, but meh..
    scatter3(RP(2:end,1), RP(2:end,2), RP(2:end,3), 10000, ...
        IREnergysumdB(chosen_m,2:end,chosen_e), 'filled');
    for ii=2:size(RP,1)
        point = [RP(ii,3) -RP(ii,1)]; point = point/norm(point);
        textangle = atan2d(point(2),point(1));
        if abs(textangle) > -1 && abs(textangle) < 1
            point = point*1.2;
        end
        if abs(textangle) > 179 && abs(textangle) < 181
            point = point*1.1;
        end
        if abs(textangle) > 89 && abs(textangle) < 91
            point = point*0.9;
        end
        if abs(textangle) < -89 && abs(textangle) > -91
            point = point*0.9;
        end
        text(RP(ii,1)+labeloffset*point(1), RP(ii,2), RP(ii,3)+labeloffset*point(2), ...
            [num2str(ii) '(' Obj.ReceiverLabel{ii} ')'], ...
            'FontSize', 9, 'HorizontalAlignment','center', 'VerticalAlignment','middle');
    end

    scatter3(RP(1,1), RP(1,2), RP(1,3), 90000, ...
        IREnergysumdB(chosen_m,1,chosen_e), 'filled');
    text(RP(1,1), RP(1,2), RP(1,3), ...
            [num2str(1) '(' Obj.ReceiverLabel{1} ')'], ...
            'FontSize', 9, 'HorizontalAlignment','center', 'VerticalAlignment','middle');


    colormap hot; % Optional: Change the color scheme (e.g., 'parula', 'turbo', 'hot')

    %create legend

    legendDescription = {'Active Emitter E(Label)'};

    %legendDescription{end+1} ='SourcePosition';
    % if ~isequal(Obj0.EmitterPosition,[0 0 0])

    %   legendDescription{end+1} = 'ReceiverPosition';

    %  end
    legendDescription{end+1} = 'Energy at Microphones';

    legend(legendEntries,legendDescription,'Location','NorthEast');
    % legend(legendEntries,legendDescription,'Location','NorthEastOutside');
    xlabel(['x (' strrep(Obj.ListenerPosition_Units, 'metre', 'mm') ')']);
    ylabel(['y (' strrep(Obj.ListenerPosition_Units, 'metre', 'mm') ')']);
    zlabel(['z (' strrep(Obj.ListenerPosition_Units, 'metre', 'mm') ')']);
    clim([-20 0]);
    cb = colorbar();
    ylabel(cb,'dB re max','FontSize',12,'Rotation',270) % Adds a color scale bar


    % formatting figures

    % Set fixed aspect ratio
    axis equal;
    % Add a little bit extra space at the axis
    % axisLimits = axis();
    % paddingSpace = 0.2 * max(abs(axisLimits(:)));
    % %paddingSpace = 0;
    % axisLimits([1 3]) = axisLimits([1 3]) - paddingSpace;
    % axisLimits([2 4]) = axisLimits([2 4]) + paddingSpace;
    axisLimits = [-33 33 -10 42 -33 33];


    axis(axisLimits);

end



function newangle = mywrapTo180(angle)
% transfer to range -180:180
newangle = mod(angle+360, 360);
if newangle > 180
    newangle = newangle-360;
end

end

