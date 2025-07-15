%BRIRListenerView - Function to load SOFA files, create and save visualizing 1 figure

% #Author: Michael Mihocic: First version of BRIRListenerView.m based on BRIRGeneral.m (01.07.2025)
% #Author: Michael Mihocic: mySOFAplotHRTF and mySOFAplotGeometry implemented (based on SOFA Toolbox functions); some improvements of code, and figure outputs (02.07.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function BRIRListenerView(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

%jw:tmp logfile
logfile="BRIRListenerView.log";
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
        disp('The SOFA file name SOFAfile is empty.');
        if isoctave; fputs(fid, [ "The SOFA file name SOFAfile is empty.\n"]); end
    else
        if isoctave; fputs(fid, [ "File: " SOFAfile "\n"]); end
    end
else
    % Use command line parameter for SOFAfile
    % disp("SOFAfile does not exist");
    disp(argv);
    arg_list = argv();
    fn = arg_list{1};
    disp(fn);
    SOFAfile = fn;
    if isoctave; fputs(fid, [ "File: " SOFAfile " or " fn]); end
end
%disp(["SOFAfile = " SOFAfile]);

%% Load SOFA file
%disp(['Loading: ' SOFAfile]);
Obj=SOFAload(SOFAfile);
[Obj] = SOFAupgradeConventions(Obj);
if isoctave; fputs(fid, [ "just done SOFA upgrade\n"]); end

SaveSOFAproperties(Obj, SOFAfile);
if isoctave; fputs(fid, ["Successfully saved SOFA details to csv files\n"]); end

if isoctave; fputs(fid, [ "About to plot\n"]); end

%% Plot MagSpectrum
close all;
figure('Name',SOFAfile);
mySOFAplotHRTF(Obj,'ETCHorizontalLV', 1);
print ('-dpng', "-r600", [SOFAfile '_1_1.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_1_1.png\n"]); end

axis manual; % prevent resizing of image in Octave
xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_1_2.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_1_2.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_1_3.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_1_3.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_1_4.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_1_4.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_1_5.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_1_5.png\n"]); end


close all;
figure('Name',SOFAfile);
mySOFAplotHRTF(Obj,'ETCHorizontalLV', 2);
% mySOFAplotHRTF(Obj,'MagMedian','nonormalization');
print ('-dpng', "-r600", [SOFAfile '_2_1.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_2_1.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_2_2.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_2_2.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_2_3.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_2_3.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_2_4.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_2_4.png\n"]); end

xlim(get(gca, 'XLim') / 2); % Scale x-axis to half
print ('-dpng', "-r600", [SOFAfile '_2_5.png']);
if isoctave;  fputs(fid, [ "just printed " SOFAfile "_2_5.png\n"]); end

%% Plot Geometry
close all;
mySOFAplotGeometry(Obj);
if isoctave; fputs(fid, [ "just done SOFAplotGeometry\n"]); end
% set(gcf, 'Name', 'SOFAfile')
% if isoctave; fputs(fid, [ "renamed figure\n"]); end
view(45,30);
if isoctave; fputs(fid, [ "adapted view\n"]); end
set(gcf, 'Position', [300, 500, 800, 500]);
if isoctave; fputs(fid, [ "adapted position\n"]); end
if isoctave; fputs(fid, [ "trying to print " SOFAfile "_3.png\n"]); end

print ('-dpng', "-r600", [SOFAfile '_3.png']);
if isoctave; fputs(fid, [ "just printed " SOFAfile "_3.png\n"]); end

%% Epilogue: (un)comment if you want to:
disp('DONE');
if isoctave; fclose(fid); end;
toc; % timer

end

function [M,meta,h]=mySOFAplotHRTF(Obj,type,varargin)

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
    % titlepostfix=' (converted to IR)';
else
    %% check if receiver selection is possible
    if R > size(Obj.Data.Real,2)
        error(['Choosen receiver out of range. Only ', num2str(size(Obj.Data.Real,2)), ' receivers recorded.'])
    end
    % titlepostfix='';
end
% if isfield(Obj, 'GLOBAL_Title') && isempty(Obj.GLOBAL_Title) == 0
%     titleprefix = [Obj.GLOBAL_Title ': '];
% else
%     titleprefix = '';
% end


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
    % Energy-time curve (ETC) in the horizontal plane, with ListenerView as parameter
    case 'etchorizontallv'
        Obj=SOFAexpand(Obj,'Data.Delay');
        hM=double(squeeze(Obj.Data.IR(:,R,:)));
        pos=Obj.ListenerView;
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
        [lv,i]=sort(pos(:,1));
        M=M2(i,:);
        if flags.do_normalization
            M=M-max(max(M));
        end
        M(M<=noisefloor)=noisefloor;
        meta.time = 0:1/fs*1000:(size(M,2)-1)/fs*1000;
        meta.lv = lv;
        % h=surface(meta.time(1:1000),lv,M(:,1:1000));
        h=surface(meta.time,lv,M(:,:));
        set(gca,'FontName','Arial','FontSize',10);
        set(gca, 'TickLength', [0.02 0.05]);
        set(gca,'LineWidth',1);
        cmap=colormap(hot);
        cmap=flipud(cmap);
        shading flat
        colormap(cmap);
        box on;
        colorbar;
        a=colorbar;
        % ylabel(a,'dB re max','FontSize',16,'Rotation',270);
        ylabel(a,'dB re max')
        xlabel('Time (ms)');
        ylabel('ListenerView (deg)');
        % title([titleprefix 'receiver: ' num2str(R)],'Interpreter','none');
        %     %%
        % case 'etchorizontal'
        %     Obj=SOFAexpand(Obj,'Data.Delay');
        %     hM=double(squeeze(Obj.Data.IR(:,R,:)));
        %     pos=Obj.SourcePosition;
        %     pos(pos(:,1)>180,1)=pos(pos(:,1)>180,1)-360;
        %     idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
        %     M=(20*log10(abs(hM(idx,:))));
        %     pos=pos(idx,:);
        %     del=round(Obj.Data.Delay(idx,R));
        %     meta.idx=idx;
        %     M2=noisefloor*ones(size(M)+[0 max(del)]);
        %     for ii=1:size(M,1)
        %         M2(ii,del(ii)+(1:Obj.API.N))=M(ii,:);
        %     end
        %     [azi,i]=sort(pos(:,1));
        %     M=M2(i,:);
        %     if flags.do_normalization
        %         M=M-max(max(M));
        %     end
        %     M(M<=noisefloor)=noisefloor;
        %     meta.time = 0:1/fs*1000:(size(M,2)-1)/fs*1000;
        %     meta.azi = azi;
        %     h=surface(meta.time,azi,M(:,:));
        %     set(gca,'FontName','Arial','FontSize',10);
        %     set(gca, 'TickLength', [0.02 0.05]);
        %     set(gca,'LineWidth',1);
        %     cmap=colormap(hot);
        %     cmap=flipud(cmap);
        %     shading flat
        %     colormap(cmap);
        %     box on;
        %     colorbar;
        %     xlabel('Time (ms)');
        %     ylabel('Azimuth (deg)');
        %     title([titleprefix 'receiver: ' num2str(R)],'Interpreter','none');
        %
        %     %%
        %     % Magnitude spectrum in the horizontal plane
        % case 'maghorizontal'
        %     pos=Obj.SourcePosition;   % copy pos to temp. variable
        %     pos(pos(:,1)>180,1)=pos(pos(:,1)>180,1)-360; % find horizontal plane
        %     idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr)); % find indices
        %     pos=pos(idx,:); % truncate pos
        %     meta.idx=idx;
        %     if do_conversion2ir == 1  % converted
        %         hM=double(squeeze(Obj.Data.IR(:,R,:)));
        %         M=(20*log10(abs(fft(hM(idx,:)')')));
        %         M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
        %         if flags.do_normalization
        %             M=M-max(max(M));
        %         end
        %
        %         M(M<noisefloor)=noisefloor;
        %         [azi,i]=sort(pos(:,1));
        %         M=M(i,:);
        %         meta.freq = 0:fs/size(hM,2):(size(M,2)-1)*fs/size(hM,2);
        %         meta.azi = azi;
        %         %         figure;
        %         h=surface(meta.freq,azi,M(:,:));
        %
        %
        %     else
        %         M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
        %         if flags.do_normalization
        %             M=M-max(max(M));
        %         end
        %         M(M<noisefloor)=noisefloor;
        %         [azi,i]=sort(pos(:,1));
        %         M=M(i,:);
        %         %         figure;
        %
        %         h=surface(Obj.N',azi,M);
        %
        %     end
        %     shading flat
        %     xlabel('Frequency (Hz)');
        %     ylabel('Azimuth (deg)');
        %     title([titleprefix 'receiver: ' num2str(R) titlepostfix],'Interpreter','none');
        %     %%
        %     % Magnitude spectrum in the median plane
        % case 'magmedian'
        %     azi=0;
        %     pos=Obj.SourcePosition;
        %     idx0=find(abs(pos(:,1))>90);
        %     pos(idx0,2)=180-pos(idx0,2);
        %     pos(idx0,1)=180-pos(idx0,1);
        %     idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
        %     pos=pos(idx,:);
        %     meta.idx=idx; % PM: TODO: check if the correct index
        %
        %     if do_conversion2ir == 1  % converted
        %
        %         hM=double(squeeze(Obj.Data.IR(:,R,:)));
        %         M=(20*log10(abs(fft(hM(idx,:)')')));
        %         M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
        %
        %         if flags.do_normalization
        %             M=M-max(max(M));
        %         end
        %         M(M<noisefloor)=noisefloor;
        %         [ele,i]=sort(pos(:,2));
        %         M=M(i,:);
        %         meta.freq = 0:fs/size(hM,2):(size(M,2)-1)*fs/size(hM,2);
        %         meta.ele = ele;
        %
        %         h=surface(meta.freq,ele,M(:,:));
        %     else
        %         M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
        %         if flags.do_normalization
        %             M=M-max(max(M)); % normalize
        %         end
        %         M(M<noisefloor)=noisefloor;
        %         [ele,i]=sort(pos(:,2));
        %         M=M(i,:);
        %         %         figure;
        %         h=surface(Obj.N',ele,M);
        %
        %     end
        %     shading flat
        %     xlabel('Frequency (Hz)');
        %     ylabel('Elevation (deg)');
        %     title([titleprefix 'receiver: ' num2str(R) titlepostfix],'Interpreter','none');
        %
        %     %%
        %     % Magnitude spectrum in the median plane
        % case 'magsagittal'
        %
        %     [lat,pol]=sph2hor(Obj.SourcePosition(:,1),Obj.SourcePosition(:,2));
        %     pos=[lat pol];
        %     idx=find(pos(:,1)<(offset+thr) & pos(:,1)>(offset-thr));
        %     pos=pos(idx,:);
        %     meta.idx=idx;
        %
        %     if do_conversion2ir == 1  % converted
        %
        %         hM=double(squeeze(Obj.Data.IR(:,R,:)));
        %         M=(20*log10(abs(fft(hM(idx,:)')')));
        %         M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
        %         if flags.do_normalization
        %             M=M-max(max(M));
        %         end
        %         M(M<noisefloor)=noisefloor;
        %         [ele,i]=sort(pos(:,2));
        %         M=M(i,:);
        %         meta.freq = 0:fs/size(hM,2):(size(M,2)-1)*fs/size(hM,2);
        %         meta.ele = ele;
        %         h=surface(meta.freq,ele,M(:,:));
        %     else
        %         M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
        %         if flags.do_normalization
        %             M=M-max(max(M));
        %         end
        %         M(M<noisefloor)=noisefloor;
        %         [ele,i]=sort(pos(:,2));
        %         M=M(i,:);
        %         h=surface(Obj.N',ele,M(:,:));
        %
        %     end
        %     shading flat
        %     xlabel('Frequency (Hz)');
        %     ylabel('Polar angle (deg)');
        %     title([titleprefix 'receiver: ' num2str(R) '; Lateral angle: ' num2str(offset) 'deg' titlepostfix],'Interpreter','none');
        %
        %     %%
        %     % ETC in the median plane
        % case 'etcmedian'
        %     %     noisefloor=-50;
        %     azi=0;
        %     Obj=SOFAexpand(Obj,'Data.Delay');
        %     hM=double(squeeze(Obj.Data.IR(:,R,:)));
        %     pos=Obj.SourcePosition;
        %     idx0=find(abs(pos(:,1))>90);
        %     pos(idx0,2)=180-pos(idx0,2);
        %     pos(idx0,1)=180-pos(idx0,1);
        %     idx=find(pos(:,1)<(azi+thr) & pos(:,1)>(azi-thr));
        %     meta.idx=idx; % PM: TODO: Check if the correct index
        %     M=(20*log10(abs(hM(idx,:))));
        %     pos=pos(idx,:);
        %     del=round(Obj.Data.Delay(idx,R));
        %     M2=zeros(size(M)+[0 max(del)]);
        %     for ii=1:size(M,1)
        %         M2(ii,del(ii)+(1:Obj.API.N))=M(ii,:);
        %     end
        %     if flags.do_normalization
        %         M=M2-max(max(M2));
        %     else
        %         M = M2;
        %     end
        %     M(M<noisefloor)=noisefloor;
        %     [ele,i]=sort(pos(:,2));
        %     M=M(i,:);
        %     meta.time = 0:1/fs*1000:(size(M,2)-1)/fs*1000;
        %     meta.ele = ele;
        %     h=surface(meta.time,ele,M(:,:));
        %     set(gca,'FontName','Arial','FontSize',10);
        %     set(gca, 'TickLength', [0.02 0.05]);
        %     set(gca,'LineWidth',1);
        %     cmap=colormap(hot);
        %     cmap=flipud(cmap);
        %     shading flat
        %     colormap(cmap);
        %     box on;
        %     colorbar;
        %     xlabel('Time (ms)');
        %     ylabel('Elevation (deg)');
        %     title([titleprefix 'receiver: ' num2str(R)],'Interpreter','none');
        %
        %     %%
        % case 'magspectrum'
        %     pos=round(Obj.SourcePosition*10)/10;
        %     switch size(dir,2)
        %         case 1
        %             aziPos = pos(:,1);
        %             aziDir=dir(:,1);
        %             aziComp = intersect(aziPos,aziDir,'rows');
        %             idx= find(ismember(aziPos,aziComp,'rows'));
        %         case 2
        %             aziPos = pos(:,1);
        %             aziDir=dir(:,1);
        %             elePos = pos(:,2);
        %             eleDir=dir(:,2);
        %             aziComp = intersect(aziPos,aziDir,'rows');
        %             eleComp = intersect(elePos,eleDir,'rows');
        %             idx=find(ismember(aziPos,aziComp,'rows') & ...
        %                 ismember(elePos,eleComp,'rows'));
        %         otherwise
        %             aziPos = pos(:,1);
        %             aziDir=dir(:,1);
        %             elePos = pos(:,2);
        %             eleDir=dir(:,2);
        %             rPos = pos(:,3);
        %             rDir=dir(:,3);
        %             aziComp = intersect(aziPos,aziDir,'rows');
        %             eleComp = intersect(elePos,eleDir,'rows');
        %             rComp = intersect(rPos,rDir,'rows');
        %             idx=find(ismember(aziPos,aziComp,'rows') & ...
        %                 ismember(elePos,eleComp,'rows') & ismember(rPos,rComp,'rows'));
        %     end
        %     if isempty(idx), error('Position not found'); end
        %     meta.idx=idx;
        %
        %     if do_conversion2ir == 1  % convert
        %         IR=squeeze(Obj.Data.IR(idx,R,:));
        %         if length(idx) > 1
        %             M=20*log10(abs(fft(IR')))';
        %             M=M(:,1:floor(size(M,2)/2));  % only positive frequencies
        %             h=plot(0:fs/2/size(M,2):(size(M,2)-1)*fs/2/size(M,2),M);
        %             for ii=1:length(idx)
        %                 labels{ii}=['#' num2str(idx(ii)) ': (' num2str(pos(idx(ii),1)) ', ' num2str(pos(idx(ii),2)) ')'];
        %             end
        %             legend(labels);
        %         else % only one curve
        %             hM=20*log10(abs(fft(IR)));
        %             M=hM(1:floor(length(hM)/2));
        %             hold on;
        %             h=plot(0:fs/2/length(M):(length(M)-1)*fs/2/length(M),M,color,...
        %                 'DisplayName',['#' num2str(idx) ': (' num2str(pos(idx,1)) ', ' num2str(pos(idx,2)) ')']);
        %             legend;
        %         end
        %         xlim([0 fs/2]);
        %         titlepostfix=' (converted to IR)';
        %     else
        %
        %         M=20*log10(abs(sqrt(squeeze(Obj.Data.Real(idx,R,:)).^2 + squeeze(Obj.Data.Imag(idx,R,:)).^2)));
        %
        %         if length(idx) > 1
        %             h=plot(Obj.N',M);
        %             for ii=1:length(idx)
        %                 labels{ii}=['#' num2str(idx(ii)) ': (' num2str(pos(idx(ii),1)) ', ' num2str(pos(idx(ii),2)) ')'];
        %             end
        %             legend(labels);
        %         else
        %             hold on;
        %             h=plot(Obj.N',M,color,...
        %                 'DisplayName',['#' num2str(idx) ': (' num2str(pos(idx,1)) ', ' num2str(pos(idx,2)) ')']);
        %             legend;
        %         end
        %         titlepostfix='';
        %     end
        %     ylabel('Magnitude (dB)');
        %     xlabel('Frequency (Hz)');
        %     ylim([max(max(M))+noisefloor-10 max(max(M))+10]);
        %     title([titleprefix 'receiver: ' num2str(R) titlepostfix],'Interpreter','none');
        %
        %     %%
        %     % Interaural time delay in the horizontal plane
        % case 'itdhorizontal'
        %
        %     if exist('OCTAVE_VERSION','builtin')
        %         warning('Command ''polarplot'' not supported by Octave (yet)!')
        %     else
        %         [itd, ~] = SOFAcalculateITD(Obj, 'time',flags.itdestimator);
        %         pos = Obj.SourcePosition;
        %         idx=find(pos(:,2)<(offset+thr) & pos(:,2)>(offset-thr));
        %         itd = itd(idx);
        %         meta.idx=idx;
        %         [pos, idx_sort] = sort(pos(idx,1));
        %         itd = itd(idx_sort);
        %         angles = deg2rad(pos);
        %         %figure('Renderer', 'painters', 'Position', [10 10 700 450]);
        %         polarplot(angles, abs(itd), 'linewidth', 1.2);
        %         ax = gca;
        %         ax.ThetaDir = 'counterclockwise';
        %         ax.ThetaZeroLocation = 'top';
        %         rticks([max(itd)*2/3, max(itd)]);
        %         rticklabels({[num2str(round(max(itd)*2/3*1e6,1)) ' ' char(181) 's'],...
        %             [num2str(round(max(itd)*1e6,1)) ' ' char(181) 's']});
        %         thetaticks(0:30:330)
        %         thetaticklabels({'0°', '30°', '60°', '90°', '120°', '150°', '180°', ...
        %             '210°', '240°','270°', '300°', '330°'});
        %         grid on;
        %     end

    otherwise
        error([type , ' no supported plotting type.'])
end
end

function mySOFAplotGeometry(Obj0,varargin)

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
            figure('Position',[1 1 w*1.2 h]*100);
            box on; hold on;
            % plot the room
            rectangle('Position',[x y w h]);
        else
            figure; hold on;
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
                %               quiver3(LP(ii,1),LP(ii,2),LP(ii,3),LU(ii,1),LU(ii,2),LU(ii,3),'Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
                %             quiver3(LP(ii,1),LP(ii,2),LP(ii,3),LV(ii,1),LV(ii,2),LV(ii,3),'Color',[1 0 0],'MarkerFaceColor',[1 0 0]);
            end
            if flags.do_normalize
                LU(1,:) = LU(1,:)./norm(LU(1,:));
            end
            legendEntries(end+1) = quiver3(LP(1,1),LP(1,2),LP(1,3),LU(1,1),LU(1,2),LU(1,3),0,'AutoScale','off','Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
            %         legendEntries(end+1) = quiver3(LP(1,1),LP(1,2),LP(1,3),LU(1,1),LU(1,2),LU(1,3),'Color',[0 0 0],'MarkerFaceColor',[0 0 0]);
            %         legendEntries(end+1) = quiver3(LP(1,1),LP(1,2),LP(1,3),LV(1,1),LV(1,2),LV(1,3),'Color',[1 0 0],'MarkerFaceColor',[1 0 0]);
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
        legend(legendEntries,legendDescription,'Location','NorthEastOutside');
        xlabel(['X (' strrep(Obj.ListenerPosition_Units, 'metre', 'm') ')']);
        ylabel(['Y (' strrep(Obj.ListenerPosition_Units, 'metre', 'm') ')']);
        zlabel(['Z (' strrep(Obj.ListenerPosition_Units, 'metre', 'm') ')']);

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
        paddingSpace = 0.2 * max(abs(axisLimits(:)));
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


function SaveSOFAproperties(Obj, SOFAfile)
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
delimiter = char(9); % Define tabulator as delimiter
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
% delimiter = char(9); % Define tabulator as delimiter
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

