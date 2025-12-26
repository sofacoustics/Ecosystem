%HRTFGeneral - Function to load SOFA files, create and save visualizing 3 figures

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: minor fixes when creating figures (03.06.2025)
% #Author: Michael Mihocic: creating more figures; using (enhanced) mySOFAplotHRTF instead of SOFA function; ITD figures created (also working in Octave) (23.06.2025)
% #Author: Michael Mihocic: creating more figures: Geometry plotted; script ready to create 8 figures (26.06.2025)
% #Author: Michael Mihocic: several updates and improvements; file renamed from HRIR3.m to HRTFGeneral.m (03.07.2025)
% #Author: Michael Mihocic: create csv files with properties (09.07.2025)
% #Author: Michael Mihocic: support for convention SimpleFreeFieldHRTF added; bug fixed when running in Matlab (18.09.2025)
% #Author: Michael Mihocic: mySOFAplotHRTF for case 'itdhorizontal' updated to compensate Obj.Data.Delay (27.10.2025)
% #Author: Piotr Majdak: added path to shared functions, moved the call to SOFA Properties to shared (27.12.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function HRTFGeneral(SOFAfile, do_print)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';
% "D:\\ISF\\Github\\SONICOM Ecosystem (sofacoustics)\\laravel\\storage\\app\\services\\1"

	addpath('../shared'); % add the path to shared functions
	logfile="HRTFGeneral.log";
	fid = fopen(logfile, "w");
	disp(['Current directory: ' pwd]);

	%% Prologue
	close all; % clean-up first
	tic; % timer
	SOFAstart('silent'); 
	warning('off'); %jw:note disable all warnings

	% Check if function called with parameter. If not, use command line parameter
	if(~exist('SOFAfile','var'))
			% Use command line parameter for SOFAfile
		arg_list = argv();
		fn = arg_list{1};
		disp(['File to be processed: ' fn]);
		SOFAfile = fn;
	end
	if(length(SOFAfile)==0)
		error('The SOFA file name is empty');
	end

	% Check if we need to print figures. Default: yes, print.
	if ~exist('do_print','var'), do_print = true; end

	%% Load SOFA file
	Obj=SOFAload(SOFAfile);

	SaveSOFAproperties(Obj, SOFAfile);
	mylog(fid, ["Successfully saved SOFA details to csv files"]);
	mylog(fid, [ "About to plot case " Obj.GLOBAL_SOFAConventions]);

	%% Plot a few figures
	switch Obj.GLOBAL_SOFAConventions
    % differ cases, depending on SOFA conventions
    case { 'SimpleFreeFieldHRIR', 'SimpleFreeFieldHRTF'}

				%graphics_toolkit fltk

        %% ITD
        f=figure;
        mySOFAplotHRTF(Obj,'itdhorizontal');
        mylog(fid, [ "plotted " SOFAfile "_7.png"]);
        myprint(do_print, [SOFAfile '_7.png']);
        mylog(fid, [ "just printed " SOFAfile "_7.png"]);
				
        %% Geometry
        mySOFAplotGeometry(Obj);
        mylog(fid, [ "just done SOFAplotGeometry"]);
        view(45,30);
        mylog(fid, [ "adapted view"]);
        title([num2str(Obj.API.M) ' Positions']);
        myprint(do_print, [SOFAfile '_8.png']);
        mylog(fid, [ "just printed " SOFAfile "_8.png"]);

        %graphics_toolkit gnuplot

        %% ETCHorizontal
				f=figure;
        mySOFAplotHRTF(Obj,'ETCHorizontal',1);
        mylog(fid, [ "just done SOFAplotHRTF ETCHorizontal 1"]);
        myprint(do_print, [SOFAfile '_1.png']);
        mylog(fid, [ "just printed " SOFAfile "_1.png"]);

        f=figure;
        mySOFAplotHRTF(Obj,'ETCHorizontal',2);
        mylog(fid, [ "just done SOFAplotHRTF ETCHorizontal 2"]);
        myprint(do_print, [SOFAfile '_2.png']);
        mylog(fid, [ "just printed " SOFAfile "_2.png"]);

        %% MagMedian, lin
        f=figure;
        mySOFAplotHRTF(Obj,'MagMedian',1);
        myprint(do_print, [SOFAfile '_3.png']);
        mylog(fid, [ "just printed " SOFAfile "_3.png"]);

        f=figure;
        mySOFAplotHRTF(Obj,'MagMedian',2);
        myprint(do_print, [SOFAfile '_4.png']);
        mylog(fid, [ "just printed " SOFAfile "_4.png"]);

        %% MagMedian, log
        f=figure;
        mySOFAplotHRTF(Obj,'MagMedianLog',1);
        mylog(fid, [ "About to save: " SOFAfile "_5.png"]);
        myprint(do_print, [SOFAfile '_5.png']);
        mylog(fid, [ "just printed " SOFAfile "_5.png"]);

        f=figure;
        mySOFAplotHRTF(Obj,'MagMedianLog',2);
        mylog(fid, [ "About to save: " SOFAfile "_6.png"]);
        myprint(do_print, [SOFAfile '_6.png']);
        mylog(fid, [ "just printed " SOFAfile "_6.png"]);

    case {'GeneralTF'}

        %graphics_toolkit gnuplot

        mylog(fid, [ "case GeneralTF"]);
        % plot magnitude spectrum in the median plane, channel 1
        f=figure;
        mySOFAplotHRTF(Obj,'MagMedian',1,'conversion2ir');
        myprint(do_print, [SOFAfile '_1.png']);
        close(f);

        f=figure;
        mySOFAplotHRTF(Obj,'MagMedian',1,'noconversion2ir');
        myprint(do_print, [SOFAfile '_2.png']);
        close(f);

    case 'GeneralFIR'

				%graphics_toolkit fltk

        mylog(fid, [ "case GeneralFIR"]);
        f=mySOFAplotGeometry(Obj);
        set(gcf, 'Name', mfilename);
        myprint(do_print, [SOFAfile '_1.png']);
        close(f);

    case 'AnnotatedReceiverAudio'
        % no plan yet for this convention ;-)

	end


	%% Epilogue: (un)comment if you want to:
	disp('DONE');
	mylog(fid, [ "### DONE ###"]);
	fclose(fid);
	toc; % timer

end

function myprint(do_print, fn)
	if do_print, print ('-dpng', '-r600', fn); end
end

function mylog (fid, msg)
	fputs(fid, msg);
	disp(msg);
end

function [M,meta,h]=mySOFAplotHRTF(Obj,type,varargin)

	isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;
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
    % mylog(fid, [ "Plot: debug check point 4"]);
    if exist('OCTAVE_VERSION','builtin')
        % We're in Octave
        % mylog(fid, [ "Plot: Octave detected"]);
        if ismember(type,{'MagHorizontal','MagMedian','MagMedianLog','MagSpectrum','MagSagittal'}) && ismember(lower(Obj.GLOBAL_SOFAConventions),{'freefielddirectivitytf','generaltf','simplefreefieldhrtf'})
            % In Octave 'contains' is not available, thus, the list has to be extended manually
            do_conversion2ir = 0;
        else
            do_conversion2ir = 1;
        end
    else
        error('Run this in Octave');
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
	else
    %% check if receiver selection is possible
    if R > size(Obj.Data.Real,2)
        error(['Choosen receiver out of range. Only ', num2str(size(Obj.Data.Real,2)), ' receivers recorded.'])
    end
	end


	%% Convert to spherical if cartesian
	if strcmp(Obj.SourcePosition_Type,'cartesian')
    % %     Obj2=Obj; % compare to old method (Obj2)
    for ii=1:Obj.API.M
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
        idx=[];
        thr = thr/2;
        while length(idx)<6 && thr<45
          thr = thr*2;
          idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
        end
        M=(20*log10(abs(hM(idx,:))));
        pos=pos(idx,:);
        del=round(Obj.Data.Delay(idx,R));
        meta.idx=idx;
        M2=noisefloor*ones(size(M)+[0 max(del)-min(del)]);
        for ii=1:size(M,1)
            M2(ii,del(ii)+(1:Obj.API.N)-min(del))=M(ii,:);
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
        ylabel('Azimuth angle (deg)');
        a=colorbar; ylabel(a,'dB re max');
        title(['Horizontal plane +/-' num2str(thr) ' degrees']);

        % Magnitude spectrum in the median plane
    case 'magmedian'
        azi=0;
        pos=Obj.SourcePosition;
        idx0=find(abs(pos(:,1))>90);
        pos(idx0,2)=180-pos(idx0,2);
        pos(idx0,1)=180-pos(idx0,1);
        idx=[]; thr=thr/2;
        while length(idx)<6 && thr<45
          thr=thr*2;
          idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
        end
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
            h=surface(Obj.N',ele,M);

        end
        shading flat
        xlabel('Frequency (Hz)');
        ylabel('Elevation angle (deg)');
        box on;
        colorbar;
        a=colorbar; ylabel(a,'dB re max');
        title(['Median plane +/-' num2str(thr) ' degrees']);

    case 'magmedianlog'
        azi=0;
        pos=Obj.SourcePosition;
        idx0=find(abs(pos(:,1))>90);
        pos(idx0,2)=180-pos(idx0,2);
        pos(idx0,1)=180-pos(idx0,1);
				idx=[]; thr=thr/2;
        while length(idx)<6 && thr<45
          thr=thr*2;
					idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
				end
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
                M=M-max(max(M)); % normalize
            end
            M(M<noisefloor)=noisefloor;
            [ele,i]=sort(pos(:,2));
            M=M(i,:);
            h=surface(Obj.N',ele,M);
        end

        set(gca, 'XScale', 'log');
        set(gca, 'XLim', [500 19000]);
        set(gca, 'XTick', [1000 2000 4000 8000 16000]);
        set(gca, 'XTickLabel', {'1000','2000','4000','8000','16000'});

        shading flat
        xlabel('Frequency (Hz)');
        ylabel('Elevation angle (deg)');
        box on;
        colorbar;
        a=colorbar; ylabel(a,'dB re max');

        % Interaural time delay in the horizontal plane
    case 'itdhorizontal'

        % Compensate IR.Delay? only neccessary if Data.Delay is in use
        if max(max(abs(Obj.Data.Delay))) > 0

            % Expand matrix (optionally)
            Obj=SOFAexpand(Obj,'Data.Delay');

            % Fetch data
            IR = Obj.Data.IR;           % (N_directions x N_channels x N_samples)
            Delay = Obj.Data.Delay;     % (N_directions x N_channels)

            [N_directions, N_channels, N_samples] = size(IR);

            % Determine the final desired length for each signal
            lengths = zeros(N_directions, N_channels);
            for dir = 1:N_directions
                for ch = 1:N_channels
                    d = Delay(dir, ch);
                    lengths(dir, ch) = N_samples + abs(d);
                end
            end
            final_length = max(lengths(:)); % All signals will be padded to this length

            % Preallocate output
            IR_delayed = zeros(N_directions, N_channels, final_length);

            for dir = 1:N_directions
                for ch = 1:N_channels
                    d = Delay(dir, ch);
                    sig = squeeze(IR(dir, ch, :)); % (N_samples x 1)
                    if d >= 0
                        sig_delayed = [zeros(d,1); sig];
                    else
                        d_abs = abs(d);
                        sig_delayed = [sig; zeros(d_abs,1)];
                    end
                    % Pad to final_length
                    sig_delayed = [sig_delayed; zeros(final_length - numel(sig_delayed), 1)];
                    IR_delayed(dir, ch, :) = sig_delayed;
                end
            end

            % Save result to Obj, update dimensions
            Obj.Data.IR = IR_delayed;
            Obj=SOFAupdateDimensions(Obj);
        end

        % Calculate ITD
        [itd, ~] = SOFAcalculateITD(Obj, 'time',flags.itdestimator);
        pos = Obj.SourcePosition;
        idx = [];
        thr = thr/2;
        while length(idx)<6 && thr<45
          thr = thr*2;
          idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
        end
        itd = itd(idx);
        meta.idx=idx;
        [pos, idx_sort] = sort(pos(idx,1));
        itd = itd(idx_sort);
        angles = deg2rad(pos);
        %figure('Renderer', 'painters', 'Position', [10 10 700 450]);
        if isoctave
            polar(angles, abs(itd*1000), 'b');
            view(-90, 90)
            rticks([round(1000*max(itd*1000))/1000*2/3, round(1000*max(itd*1000))/1000]);
            text(max(itd)*1000, max(itd)*1000, 'ITD (ms)');
            text(-max(itd)*1000, max(itd)*1000, ['Horizontal plane +/- ' num2str(thr) ' degrees']);
            grid on;
        else
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

function f = mySOFAplotGeometry(Obj0,varargin)

	definput.keyvals.index=1:Obj0.API.M;
	definput.keyvals.shorder=Inf;
	definput.keyvals.shm=Inf;
	definput.flags.normalize={'normalize','original'};
	argin=varargin;
	for ii=1:length(argin)
			if ischar(argin{ii}), argin{ii}=lower(argin{ii}); end
	end
	[flags,kv] = SOFAarghelper({'index','shorder','shm'},definput,argin);
	index = kv.index;
	SHorder=kv.shorder;
	SHm=kv.shm;
	flags.do_normalize = flags.normalize;

	if any(index > Obj0.API.M)
			error(['Index out of range. Only ', num2str(Obj0.API.M), ...
					' measurement(s) performed.'])
	elseif any(index < 1)
			error('Choose index to be >= 1.')
	end

	switch Obj0.GLOBAL_SOFAConventions
    %%
    % case{'AnnotatedReceiverAudio'}
    %     figure;
    %     X=Obj0.M;
    %     Y=zeros(size(X));
    %     Z=Y;
    %     U=Obj0.ListenerView(:,1);
    %     V=Obj0.ListenerView(:,2);
    %     W=Obj0.ListenerView(:,3);
    %     qV=quiver3(X,Y,Z,U,V,W,'r');
    %     qV.ShowArrowHead = 'off';
    %     hold on;
    %     U=Obj0.ListenerUp(:,1);
    %     V=Obj0.ListenerUp(:,2);
    %     W=Obj0.ListenerUp(:,3);
    %     qU=quiver3(X,Y,Z,U,V,W,'b');
    %     qU.ShowArrowHead = 'off';
    %     qU.Marker = '.';
    %     view(0,90);
    %     xlabel([Obj0.M_LongName ' (in ' Obj0.M_Units ')']);
    %     legend({'ListenerView','ListenerUp'});
    %     rotate3d on
    case {'SimpleFreeFieldHRTF','SimpleFreeFieldHRIR','SingleRoomDRIR','FreeFieldDirectivityTF','GeneralFIR','GeneralTFE','FreeFieldHRIR','FreeFieldHRTF','GeneralTF-E','SingleRoomMIMOSRIR','SingleRoomSRIR'}
        % Expand entries to the same number of measurement points
        Obj = SOFAexpand(Obj0);
        % See if the room geometry is specified
        if strcmpi(Obj.GLOBAL_RoomType,'shoebox')
            x = min(Obj.RoomCornerA(1), Obj.RoomCornerB(1));
            xd = max(Obj.RoomCornerA(1), Obj.RoomCornerB(1));
            y = min(Obj.RoomCornerA(2), Obj.RoomCornerB(2));
            yd = max(Obj.RoomCornerA(2), Obj.RoomCornerB(2));
            w = xd - x;
            h = yd - y;
            f=figure('Position',[1 1 w*1.2 h]*100);
            box on; hold on;
            % plot the room
            rectangle('Position',[x y w h]);
        else
            f=figure; hold on;
        end

        legendEntries = [];
        % title(sprintf('%s, %s',Obj.GLOBAL_SOFAConventions,Obj.GLOBAL_RoomType));

        % Get ListenerPosition, ReceiverPosition, SourcePosition, and
        % EmitterPosition
        % NOTE: ListenerPosition is set to [0 0 0] for SimpleFreeFieldHRIR
        LP = SOFAconvertCoordinates(Obj.ListenerPosition(index,:),Obj.ListenerPosition_Type,'cartesian');
        if ~(strcmpi(Obj.ReceiverPosition_Type,'Spherical Harmonics'))
            if size(Obj.ReceiverPosition,3)==1, idx=1; else idx=index; end
            RP = SOFAconvertCoordinates(Obj.ReceiverPosition(:,:,idx),Obj.ReceiverPosition_Type,'cartesian');
        end
        if size(Obj.SourcePosition,1)==1, idx=1; else idx=index; end
        SP = SOFAconvertCoordinates(Obj.SourcePosition(idx,:),Obj.SourcePosition_Type,'cartesian');
        if ~(strcmpi(Obj.EmitterPosition_Type,'Spherical Harmonics'))
            if size(Obj.EmitterPosition,3)==1, idx=1; else idx=index; end
            EP = SOFAconvertCoordinates(Obj.EmitterPosition(:,:,idx),Obj.EmitterPosition_Type,'cartesian');
        end
        if isfield(Obj,'ListenerView')
            if size(Obj.ListenerView,1)==1, idx=1; else idx=index; end
            LV = SOFAconvertCoordinates(Obj.ListenerView(idx,:),Obj.ListenerView_Type,'cartesian');
        end
        if isfield(Obj,'ListenerUp')
            try
                if size(Obj.ListenerUp,1)==1, idx=1; else idx=index; end
                LU = SOFAconvertCoordinates(Obj.ListenerUp(idx,:),Obj.ListenerUp_Type,'cartesian');
            catch
                % if listerUp_type is not defined try using listenerView_type
                % instead
                if size(Obj.ListenerUp,1)==1, idx=1; else idx=index; end
                LU = SOFAconvertCoordinates(Obj.ListenerUp(idx,:),Obj.ListenerView_Type,'cartesian');
            end
        end
        if isfield(Obj,'SourceView')
            if size(Obj.SourceView,1)==1, idx=1; else idx=index; end
            SV  = SOFAconvertCoordinates(Obj.SourceView(idx,:),Obj.SourceView_Type,'cartesian');
        end
        if isfield(Obj,'SourceUp')
            try
                if size(Obj.SourceUp,1)==1, idx=1; else idx=index; end
                SU = SOFAconvertCoordinates(Obj.SourceUp(idx,:),Obj.SourceUp_Type,'cartesian');
            catch
                if size(Obj.SourceUp,1)==1, idx=1; else idx=index; end
                SU = SOFAconvertCoordinates(Obj.SourceUp(idx,:),Obj.SourceView_Type,'cartesian');
            end
        end
        % Use only unique listener and source positons
        caseString = '';
        uniquePoints = [LP SP];
        if exist('LV')
            uniquePoints = [uniquePoints LV];
            caseString = strcat(caseString , 'LV');
        end
        if exist('LU')
            uniquePoints = [uniquePoints LU];
            caseString = strcat(caseString, 'LU');
        end
        if exist('SV')
            uniquePoints = [uniquePoints SV];
            caseString = strcat(caseString, 'SV');
        end
        if exist('SU')
            uniquePoints = [uniquePoints SU];
            caseString = strcat(caseString, 'SU');
        end

        uniquePoints = unique(uniquePoints,'rows');
        switch caseString
            case ''
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
            case 'LV'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                LV = uniquePoints(:,7:9);
            case 'LVLU'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                LV = uniquePoints(:,7:9);
                %             LU = uniquePoints(:,7:9); % I think this was a bug (miho)
                LU = uniquePoints(:,10:12);
            case 'LVLUSV'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                LV = uniquePoints(:,7:9);
                LU = uniquePoints(:,10:12);
                SV = uniquePoints(:,13:15);
            case 'SV'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                SV = uniquePoints(:,7:9);
            case 'SVSU'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                SV = uniquePoints(:,7:9);
                SU = uniquePoints(:,10:12);
            case 'LVSV'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                LV = uniquePoints(:,7:9);
                SV = uniquePoints(:,10:12);
            case 'LVSVSU'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                LV = uniquePoints(:,7:9);
                SV = uniquePoints(:,10:12);
                SU = uniquePoints(:,13:15);
            case 'LVLUSVSU'
                LP = uniquePoints(:,1:3);
                SP = uniquePoints(:,4:6);
                LV = uniquePoints(:,7:9);
                LU = uniquePoints(:,10:12);
                SV = uniquePoints(:,13:15);
                SU = uniquePoints(:,16:18);
            otherwise
                error('This SOFAConventions is not supported for plotting');
        end

        % Plot ListenerPosition
        legendEntries(end+1) = plot3(LP(:,1),LP(:,2),LP(:,3),'ro','MarkerFaceColor','r','MarkerSize',5);
        if strcmpi(Obj.ReceiverPosition_Type,'Spherical Harmonics')
            maxSHorder = sqrt(Obj.API.R)-1;
            % set SHorder to max if user didn't specify it
            if isinf(SHorder)
                SHorder = maxSHorder;
            end
            % check if chosen SHorder is possible
            if SHorder > maxSHorder
                error(['Chosen SHorder not possibile, only orders up to ', ...
                    num2str(maxSHorder), ' possible.'])
            elseif SHorder < 0
                error('Chosen SHorder not possibile, as it must be positive.')
            end
            x0 = Obj.ListenerPosition(1,1);
            y0 = Obj.ListenerPosition(1,2);
            z0 = Obj.ListenerPosition(1,3);

            % check for m given by the user and if it is possible
            if isinf(SHm)
                % if not set to some value
                SHm = -floor(1/2 * SHorder);
            elseif abs(SHm) > SHorder
                error(['Chosen SHm not possibile, must be in range of abs(', ...
                    num2str(SHorder), ').'])
            end
            % if possibile set SHmForPlotting
            SHmForPlotting = power(SHorder,2)+SHorder+SHm+1;

            [X,Y,Z] = sphere(50);
            [azi_rad,elev_rad,~] = cart2sph(X,Y,Z);
            azi_length =size(azi_rad,1);
            elev_length=size(elev_rad,1);
            azi= azi_rad/pi*180;
            elev = elev_rad/pi*180;
            azi = azi(:);
            elev = elev(:);

            S = sph2SH([azi,elev], SHorder);
            S = S(:,SHmForPlotting);
            S = reshape(S,[azi_length,elev_length]);

            r_sphere = 0.7*max(max(S))*randi(2,size(S));
            r = abs(S) + r_sphere;

            [D_x,D_y,D_z] = sph2cart(azi_rad,elev_rad,abs(r));
            legendEntries(end+1) = surf(D_x+x0,D_y+y0,D_z+z0,Y,'LineStyle','none','FaceAlpha',0.09);
            %     elseif strcmpi(Obj.ReceiverPosition_Type,'spherical')
            %         S = sqrt(Obj.API.R-1);
            %         x0 = Obj.ListenerPosition(1,1);
            %         y0 = Obj.ListenerPosition(1,2);
            %         theta = -pi : 0.01 : pi;
            %         r = 1;
            %         phi = sin(S*theta);
            %         phi_negativ = sin(-S*theta);
            %
            %         [x,y] = pol2cart(theta,(r*(1+ abs(phi)+ abs(phi_negativ)))./3);
            %         legendEntries(end+1)=plot(x+x0,y+y0,'LineStyle','--','Color',[0.741 0.747 0.741]);
            %
            % %         text(x0,y0+r,['Order: ',num2str(S)],'HorizontalAlignment',...
            % %            'center','VerticalAlignment','bottom')

        else
            % Plot ReceiverPositon (this is plotted only for the first ListenerPosition)
            if ndims(RP)>2
                % If ReceiverPosition has more than two dimensions reduce it to the first
                % ListenerPosition
                RP = shiftdim(RP,2);
                RP = squeeze(RP(1,:,:));
                RP = reshape(RP,[size(Obj.ReceiverPosition,1), Obj.API.C]);
            end
            legendEntries(end+1) = plot3(LP(1,1)+RP(1,1), LP(1,2)+RP(1,2), LP(1,3)+RP(1,3),'r*','MarkerSize',8);
            for ii=2:size(RP,1)
                plot3(LP(1,1)+RP(ii,1), LP(1,2)+RP(ii,2), LP(1,3)+RP(ii,3),'r*','MarkerSize',8);
            end
        end
        % Plot SourcePosition
        legendEntries(end+1)=plot3(SP(:,1),SP(:,2),SP(:,3),'b.','MarkerSize',7);
        % Plot EmitterPositions depending on Type
        if strcmpi(Obj.EmitterPosition_Type,'Spherical Harmonics')
            maxSHorder = sqrt(Obj.API.E)-1;
            % set SHorder to max if user didn't specify it
            if isinf(SHorder)
                SHorder = maxSHorder;
            end
            % check if chosen SHorder is possible
            if SHorder > maxSHorder
                error(['Chosen SHorder not possibile, only orders up to ', ...
                    num2str(maxSHorder), ' possible.'])
            elseif SHorder < 0
                error('Chosen SHorder not possibile, as it must be positive.')
            end
            x0 = Obj.SourcePosition(1,1);
            y0 = Obj.SourcePosition(1,2);
            z0 = Obj.SourcePosition(1,3);

            % check for m given by the user
            if isinf(SHm)
                SHm = -floor(1/2 * SHorder);
            elseif abs(SHm) > SHorder
                error(['Chosen SHm not possibile, must be in range of abs(', ...
                    num2str(SHorder), ').'])
            end
            % if possibile set SHmForPlotting
            SHmForPlotting = power(SHorder,2)+SHorder+SHm+1;

            [X,Y,Z] = sphere(50);
            [azi_rad,elev_rad,~] = cart2sph(X,Y,Z);
            azi_length =size(azi_rad,1);
            elev_length=size(elev_rad,1);
            azi= azi_rad/pi*180;
            elev = elev_rad/pi*180;
            azi = azi(:);
            elev = elev(:);

            S = sph2SH([azi,elev], SHorder);
            S = S(:,SHmForPlotting);
            S = reshape(S,[azi_length,elev_length]);

            r_sphere = 0.7*max(max(S))*randi(2,size(S));
            r = abs(S) + r_sphere;

            [D_x,D_y,D_z] = sph2cart(azi_rad,elev_rad,abs(r));
            legendEntries(end+1) = surf(D_x+x0,D_y+y0,D_z+z0,Y,'LineStyle','none','FaceAlpha',0.09);

            %     elseif strcmpi(Obj.EmitterPosition_Type,'spherical')
            %         S = sqrt(Obj.API.R-1);
            %         x0 = Obj.SourcePosition(1,1);
            %         y0 = Obj.SourcePosition(1,2);
            %         theta = -pi : 0.01 : pi;
            %         r = 1;
            %         phi = sin(S*theta);
            %         phi_negativ = sin(-S*theta);
            %
            %         [x,y] = pol2cart(theta,(r*(1+ abs(phi)+ abs(phi_negativ)))./3);
            %         legendEntries(end+1)=plot(x+x0,y+y0,'LineStyle','--','Color',[0.741 0.747 0.741]);
            %
            % %         text(x0,y0+r,['Order: ',num2str(S)],'HorizontalAlignment',...
            % %            'center','VerticalAlignment','bottom')

        else
            % Plot EmitterPosition
            if ~isequal(Obj0.EmitterPosition,[0 0 0]) % plot only if not simple emitter in the source's center
                if ndims(EP)>2
                    % If EmitterPosition has more than two dimensions reduce it to the first
                    % ListenerPosition
                    EP = shiftdim(EP,2);
                    EP = squeeze(EP(1,:,:));
                    EP = reshape(EP,[size(Obj.EmitterPosition,1), Obj.API.C]);
                end
                % plot Emitters for first Source
                legendEntries(end+1) = plot3(SP(1,1)+EP(1,1), SP(1,2)+EP(1,2), SP(1,3)+EP(1,3),'b+','MarkerSize',8);
                for ii=2:size(EP,1)
                    plot3(SP(1,1)+EP(ii,1), SP(1,2)+EP(ii,2), SP(1,3)+EP(ii,3),'b+','MarkerSize',8);
                end
                % plot all Emitters for each Source
                for jj=2:size(SP,1)
                    for ii=1:size(EP,1)
                        plot3(SP(jj,1)+EP(ii,1), SP(jj,2)+EP(ii,2), SP(jj,3)+EP(ii,3),'b+');
                    end
                end
            end
        end
        if exist('LV','var')
            % Plot ListenerView
            LV=unique(LV,'rows');
            for ii = 2:size(LV,1)
                % Scale size of ListenerView vector smaller
                if flags.do_normalize
                    LV(ii,:) = LV(ii,:)./norm(LV(ii,:));
                end
                % Plot line for ListenerView vector
                quiver3(LP(ii,1),LP(ii,2),LP(ii,3),LV(ii,1),LV(ii,2),LV(ii,3),'Color',[1 0 0],'MarkerFaceColor',[1 0 0]);
            end
            if flags.do_normalize
                LV(1,:) = LV(1,:)./norm(LV(1,:));
            end
            legendEntries(end+1) = quiver3(LP(1,1),LP(1,2),LP(1,3),LV(1,1),LV(1,2),LV(1,3),'Color',[1 0 0],'MarkerFaceColor',[1 0 0]);
        end
        if exist('LU','var')
            LU=unique(LU,'rows');
            for ii = 2:size(LU,1)
                if flags.do_normalize
                    LU(ii,:) = LU(ii,:)./norm(LU(ii,:));
                end
                quiver3(LP(ii,1),LP(ii,2),LP(ii,3),LU(ii,1),LU(ii,2),LU(ii,3),0,'AutoScale','off','Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
            end
            if flags.do_normalize
                LU(1,:) = LU(1,:)./norm(LU(1,:));
            end
            legendEntries(end+1) = quiver3(LP(1,1),LP(1,2),LP(1,3),LU(1,1),LU(1,2),LU(1,3),0,'AutoScale','off','Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
        end
        if exist('SV','var')
            SV=unique(SV,'rows');
            % Plot ListenerView
            for ii = 2:size(SV,1)
                % Scale size of ListenerView vector smaller
                if flags.do_normalize
                    SV(ii,:) = SV(ii,:)./norm(SV(ii,:));
                end
                % Plot line for ListenerView vector
                quiver3(SP(ii,1),SP(ii,2),SP(ii,3),SV(ii,1),SV(ii,2),SV(ii,3),0,...
                    'AutoScale','off',...
                    'Color',[0 0 1],'MarkerFaceColor',[0 0 1]);
            end
            if flags.do_normalize
                SV(1,:) = SV(1,:)./norm(SV(1,:));
            end
            legendEntries(end+1) = quiver3(SP(1,1),SP(1,2),SP(1,3),SV(1,1),SV(1,2),SV(1,3),0,...
                'AutoScale','off',...
                'Color',[0 0 1],'MarkerFaceColor',[0 0 1]);
        end
        if exist('SU','var')
            SU=unique(SU,'rows');
            for ii = 2:size(SU,1)
                if flags.do_normalize
                    SU(ii,:) = SU(ii,:)./norm(SU(ii,:));
                end
                quiver3(SP(ii,1),SP(ii,2),SP(ii,3),SU(ii,1),SU(ii,2),SU(ii,3),0,...
                    'AutoScale','off',...
                    'Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
            end
            if flags.do_normalize
                SU(1,:) = SU(1,:)./norm(SU(1,:));
            end
            legendEntries(end+1) = quiver3(SP(1,1),SP(1,2),SP(1,3),SU(1,1),SU(1,2),SU(1,3),'Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
        end
        % create legend
        legendDescription = {'ListenerPosition'};
        if (strcmpi(Obj.ReceiverPosition_Type,'Spherical Harmonics'))
            legendDescription{end+1} = ['Receiver (order: ', num2str(S_R) ,')'];
        else
            legendDescription{end+1} = 'ReceiverPosition';
        end
        legendDescription{end+1} ='SourcePosition';
        if ~isequal(Obj0.EmitterPosition,[0 0 0])
            if (strcmpi(Obj.EmitterPosition_Type,'Spherical Harmonics'))
                legendDescription{end+1} = ['Emitter (order: ', num2str(SHorder),', m: ', num2str(SHm),')'];
            else
                legendDescription{end+1} = 'EmitterPosition';
            end
        end
        if exist('LV','var')
            legendDescription{end+1} = 'ListenerView';
        end
        if exist('LU','var')
            legendDescription{end+1} = 'ListenerUp';
        end
        if exist('SV','var')
            legendDescription{end+1} = 'SourceView';
        end
        if exist('SU','var')
            legendDescription{end+1} = 'SourceUp';
        end
        legend(legendEntries,legendDescription,'Location','NorthEast');
        % legend(legendEntries,legendDescription,'Location','NorthEastOutside');
        xlabel(['x (' strrep(Obj.ListenerPosition_Units, 'metre', 'm') ')']);
        ylabel(['y (' strrep(Obj.ListenerPosition_Units, 'metre', 'm') ')']);
        zlabel(['z (' strrep(Obj.ListenerPosition_Units, 'metre', 'm') ')']);

    otherwise
        error('This SOFAConventions is not supported for plotting');
	end

%% formatting figures
	switch Obj0.GLOBAL_SOFAConventions

    case{'AnnotatedReceiverAudio'}
        % Add a little bit extra space at the axis
        axisLimits = axis();
        paddingSpaceX = 0.05 * abs(axisLimits(2) - axisLimits(1));
        paddingSpaceY = 0.05 * abs(axisLimits(4) - axisLimits(3));
        axisLimits(1) = axisLimits(1) - paddingSpaceX;
        axisLimits(2) = axisLimits(2) + paddingSpaceX;
        axisLimits(3) = axisLimits(3) - paddingSpaceY;
        axisLimits(4) = axisLimits(4) + paddingSpaceY;

    otherwise
        % Set fixed aspect ratio
        axis equal;
        % Add a little bit extra space at the axis
        axisLimits = axis();
        % paddingSpace = 0.2 * max(abs(axisLimits(:)));
        paddingSpace = 0;
        axisLimits([1 3]) = axisLimits([1 3]) - paddingSpace;
        axisLimits([2 4]) = axisLimits([2 4]) + paddingSpace;
	end

	axis(axisLimits);

end

function newangle = mywrapTo180(angle)
	% transfer to range -180:180
	newangle = mod(angle+360, 360);
	if newangle > 180
			newangle = newangle-360;
	end

end