%HRIR3 - Function to load SOFA files, create and save visualizing 3 figures

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: minor fixes when creating figures (03.06.2025)
% #Author: Michael Mihocic: creating more figures; using (enhanced) mySOFAplotHRTF instead of SOFA function; ITD figures created (also working in Octave) (23.06.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function HRIR3(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

%jw:tmp logfile
logfile="HRIR3.log";
isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;
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

if isoctave;  fputs(fid, [ "About to plot\n"]); end

%% Plot a few figures
switch Obj.GLOBAL_SOFAConventions
    % differ cases, depending on SOFA conventions
    case 'SimpleFreeFieldHRIR'

        %% ETCHorizontal
        if isoctave;  fputs(fid, [ "case SimpleFreeFieldHRIR\n"]);end
        % plot ETC horizontal plane
        figure('Name',SOFAfile);
        % if isoctave;  fputs(fid, [ "just done figure\n"]); end
        mySOFAplotHRTF(Obj,'ETCHorizontal',1);
        if isoctave;  fputs(fid, [ "just done SOFAplotHRTF\n"]); end
        print ("-r600", [SOFAfile '_1.png']);
        %print ("-r600", '/tmp/hrtf_1.png');
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "_1.png\n"]); end

        if isoctave;  fputs(fid, [ "case SimpleFreeFieldHRIR\n"]); end
        % plot ETC horizontal plane
        figure('Name',SOFAfile);
        % if isoctave;  fputs(fid, [ "just done figure\n"]); end
        mySOFAplotHRTF(Obj,'ETCHorizontal',2);
        if isoctave;  fputs(fid, [ "just done SOFAplotHRTF\n"]); end
        print ("-r600", [SOFAfile '_2.png']);
        %print ("-r600", '/tmp/hrtf_1.png');
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "_2.png\n"]); end

        %% MagMedian, lin
        figure('Name',SOFAfile);
        mySOFAplotHRTF(Obj,'MagMedian',2);
        print ("-r600", [SOFAfile '_3.png']);
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "_3.png\n"]); end

        figure('Name',SOFAfile);
        mySOFAplotHRTF(Obj,'MagMedian','nonormalization');
        print ("-r600", [SOFAfile '_4.png']);
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "4.png\n"]); end

        %% MagMedian, log
        figure('Name',SOFAfile);
        mySOFAplotHRTF(Obj,'MagMedianLog',2);
        print ("-r600", [SOFAfile '_5.png']);
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "_5.png\n"]); end

        figure('Name',SOFAfile);
        mySOFAplotHRTF(Obj,'MagMedianLog','nonormalization');
        print ("-r600", [SOFAfile '_6.png']);
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "_6.png\n"]); end

        figure('Name',SOFAfile);
        mySOFAplotHRTF(Obj,'itdhorizontal');
        print ("-r600", [SOFAfile '_7.png']);
        if isoctave;  fputs(fid, [ "just printed " SOFAfile "_7.png\n"]); end

        % plot geometry
        %  SOFAplotGeometry(Obj);
        %  title(['Geometry SimpleFreeFieldHRIR, ' num2str(Obj.API.M) ' position(s)'])
        %  set(gcf, 'Name', SOFAfile);
        %  print ("-r600", [SOFAfile '_4.png']);

        % plot geometry, only show every 45th measurement
        %  index = 1:45:Obj.API.M;
        %  SOFAplotGeometry(Obj,index);
        %  title(['Geometry SimpleFreeFieldHRIR, reduced to ' num2str(size(index,2)) ' position(s)'])
        %  set(gcf, 'Name', SOFAfile);
        %  print ("-r600", [SOFAfile '_5.png']);

    case 'GeneralTF'
        fputs(fid, [ "case GeneralTF\n"]);
        % plot magnitude spectrum in the median plane, channel 1
        figure('Name',SOFAfile);
        mySOFAplotHRTF(Obj,'MagMedian',1,'conversion2ir');
        print ("-r600", [SOFAfile '_1.png']);

        figure('Name',mfilename);
        mySOFAplotHRTF(Obj,'MagMedian',1,'noconversion2ir');
        print ("-r600", [SOFAfile '_2.png']);


    case 'GeneralFIR'
        fputs(fid, [ "case GeneralFIR\n"]);
        SOFAplotGeometry(Obj);
        title(['Geometry GeneralFIR, ' num2str(Obj.API.R) ' receiver(s), ' num2str(Obj.API.M) ' position(s)'])
        set(gcf, 'Name', mfilename);
        print ("-r600", [SOFAfile '_1.png']);

    case 'AnnotatedReceiverAudio'
        % no plan yet for this convention ;-)

end


%% Epilogue: (un)comment if you want to:
disp('DONE');
fclose(fid);
toc; % timer



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

            h=surface(Obj.N',azi,M);

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

    case 'magmedianlog'
        azi=0;
        pos=Obj.SourcePosition;
        idx0=find(abs(pos(:,1))>90);
        pos(idx0,2)=180-pos(idx0,2);
        pos(idx0,1)=180-pos(idx0,1);
        idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
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
            set(gca, 'XScale', 'log');
            xlim([1e3 18e3]);
            set(gca, 'XTick', [2000 4000 8000 16000]);
            set(gca, 'XTickLabel', {'2000','4000','8000','16000'});

        else
            M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
            if flags.do_normalization
                M=M-max(max(M)); % normalize
            end
            M(M<noisefloor)=noisefloor;
            [ele,i]=sort(pos(:,2));
            M=M(i,:);
            h=surface(Obj.N',ele,M);
            set(gca, 'XScale', 'log');
            xlim([1e3 18e3]);
            set(gca, 'XTick', [2000 4000 8000 16000]);
            set(gca, 'XTickLabel', {'2000','4000','8000','16000'});

        end

        shading flat
        xlabel('Frequency (Hz)');
        ylabel('Elevation (deg)');
        title([titleprefix 'receiver: ' num2str(R) titlepostfix ' (log scale)'],'Interpreter','none');




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

        [itd, ~] = SOFAcalculateITD(Obj, 'time',flags.itdestimator);
        pos = Obj.SourcePosition;
        idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
        itd = itd(idx);
        meta.idx=idx;
        [pos, idx_sort] = sort(pos(idx,1));
        itd = itd(idx_sort);
        angles = deg2rad(pos);
        %figure('Renderer', 'painters', 'Position', [10 10 700 450]);
        if isoctave
            polar(angles, abs(itd), 'b');
            view(-90, 90)
            % title([titleprefix 'ITD (|Δt|)'], 'Interpreter', 'none');
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
