%Create Figures - Function to load SOFA files, create and save visualizing figures

% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function CreateFigures(SOFAfile)
% for debug purpose comment function row above, and uncomment this one:
% SOFAfile= 'hrtf_nh4.sofa';

%% Prologue: (un)comment here if you want to:
% clc; % clean-up first
close all; % clean-up first
tic; % timer
SOFAstart; % remove this optionally
% warning('off','SOFA:upgrade');
% warning('off','SOFA:load');
% warning('off','SOFA:save');
% warning('off','SOFA:save:API');

%% Load SOFA file
disp(['Loading: ' SOFAfile]);
Obj=SOFAload(SOFAfile);

%% Plot a few figures
switch Obj.GLOBAL_SOFAConventions
    % differ cases, depending on SOFA conventions
    case 'SimpleFreeFieldHRIR'

        % plot ETC horizontal plane
        figure('Name',SOFAfile);
        SOFAplotHRTF(Obj,'ETCHorizontal',1);
        print ("-r600", [SOFAfile '_1.png']);

        % plot magnitude spectrum in the median plane, channel 2
        figure('Name',SOFAfile);
        SOFAplotHRTF(Obj,'MagMedian',2);
        print ("-r600", [SOFAfile '_2.png']);

        % plot non-normalized magnitude spectrum in the median plane, channel 1
        figure('Name',SOFAfile);
        SOFAplotHRTF(Obj,'MagMedian','nonormalization');
        print ("-r600", [SOFAfile '_3.png']);

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
        % plot magnitude spectrum in the median plane, channel 1
        figure('Name',SOFAfile);
        SOFAplotHRTF(Obj,'MagMedian',1,'conversion2ir');
        print ("-r600", [SOFAfile '_1.png']);

        figure('Name',mfilename);
        SOFAplotHRTF(Obj,'MagMedian',1,'noconversion2ir');
        print ("-r600", [SOFAfile '_2.png']);

    case 'GeneralFIR'
        SOFAplotGeometry(Obj);
        title(['Geometry GeneralFIR, ' num2str(Obj.API.R) ' receiver(s), ' num2str(Obj.API.M) ' position(s)'])
        set(gcf, 'Name', mfilename);
        print ("-r600", [SOFAfile '_1.png']);

    case 'AnnotatedReceiverAudio'
        % no plan yet for this convention ;-)

end


%% Epilogue: (un)comment if you want to:
disp('DONE');
toc; % timer
