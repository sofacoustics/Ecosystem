%SRIRGeneral - Visualizes geometries
% This script creates for the first 20 M's four views of the same the geometry of the measurement
% The filename syntax is 'SOFAfile_DIM_Midx_Mmax=M.png'
% with
%   SOFAfile: the filename of the input file
%   DIM: iso, xz, yz, xy: the four views
%   Midx: the index of the measurement
%   M: the total number of measuerements display (limited to 20).
%
% #Author: Michael Mihocic: First version, loading and plotting a few figures, supporting a few conventions (31.08.2023)
% #Author: Michael Mihocic: support of SRIRGeometry, SingleRoomMIMOSRIR SOFA files implemented (14.04.2025)
% #Author: Michael Mihocic: conventions restriction removed (03.06.2025)
% #Author: Michael Mihocic: file renamed from SRIRGeometry.m to SRIRGeneral.m (03.07.2025)
% #Author: Michael Mihocic: geometry figures enhanced, several figures and several views stored as png; plotting HRTFs removed (07.07.2025)
% #Author: Michael Mihocic: logging improved (14.07.2025)
% #Author: Piotr Majdak: major rework because of problems in plotting the room (25.12.2025).
% #Author: Piotr Majdak: added path to shared functions, moved the call to SOFA Properties to shared (27.12.2025)
% #Author: Michael Mihocic: SOFA file is loaded with flag 'nodata'; camup set based on ListenerUp for iso and plane views (24.09.2026)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing  permissions and limitations under the License.

function SRIRGeneral(SOFAfile)

	addpath('../shared'); % add the path to shared functions
  logfile="SRIRGeneral.log";
  fid = fopen(logfile, "w");
  s = pwd;
  disp(["pwd = " s]);

  tic; % timer
  SOFAstart('silent'); % remove this optionally
  warning('off'); % disable all warnings

  % Check if function called with parameter. If not, use command line parameter
  if(exist("SOFAfile",'var'))
      if(isempty(SOFAfile))
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
  delete([SOFAfile '_*.png']); % delete all previous images

  %% Load SOFA file
  Obj=SOFAload(SOFAfile,'nodata');
  [Obj] = SOFAupgradeConventions(Obj);

  SaveSOFAproperties(Obj, SOFAfile);
  fputs(fid, ["Successfully saved SOFA details to csv files\n"]);

  % Determine "up" direction from ListenerUp for camera orientation.
  % Default: Z-up [0 0 1] (standard SOFA convention).
  % camup() is applied selectively so that the ListenerUp axis appears vertical in the rendered images. It is only applied where the up vector is NOT parallel to the view direction (parallel camup is undefined):
  
  if isfield(Obj, 'ListenerUp')
      upVec = Obj.ListenerUp(1,:);
      upVec = upVec ./ norm(upVec);
  else
      upVec = [0 0 1];
  end
  [~, upAxis] = max(abs(upVec)); % 1=X-up, 2=Y-up, 3=Z-up (default)

  % ein geometry figure pro M: file name _x_y (y=anzahl M)
  M=min(20, Obj.API.M); % plot for up to 20 M's only
  for Midx = 1:M
      SOFAplotGeometry(Obj,'index',Midx);
      %mySOFAplotGeometry(Obj,Midx);
      title(''); % remove the title done automatically by SOFAplotGeometry
      v=view;

      hx = get(gca, 'XLabel'); hy = get(gca, 'YLabel'); hz = get(gca, 'ZLabel');

      % ISO view: 3D perspective.
      set(gca, 'CameraUpVectorMode', 'auto');
      view(45,30);
      if upAxis ~= 3
          set(gca, 'CameraUpVector', upVec);
          % Apply manual alignments to fix Octave's 3D camup label overlap bug
          set(hx, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'left');
          set(hy, 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'right');
          set(hz, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'right');
      end
      legend('Location', 'eastoutside');
      print ('-dpng', "-r600", "-S2500,1560", "-F:16", [SOFAfile '_iso_' num2str(Midx) '_Mmax=' num2str(M) '.png']);
      fputs(fid, [ "  printed figure 1/4: " SOFAfile '_iso_' num2str(Midx) '_Mmax=' num2str(M) ".png\n"]);

      % XZ view: looking along Y axis (X horizontal, Z vertical for Z-up).
      set(gca, 'CameraUpVectorMode', 'auto');
      view(0,0);
      if upAxis ~= 3
          % Manual layout for 2D view (required because ISO view locked auto-layout)
          if upAxis == 1 % X-up: Z is horizontal bottom, X is vertical left
              set(hx, 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'center');
              set(hz, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'center');
          else % Y-up: X is horizontal bottom, Z is vertical left
              set(hx, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'center');
              set(hz, 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'center');
          end
      end
      if upAxis == 1, set(gca, 'CameraUpVector', upVec); end
      legend('off');
      print ('-dpng', "-r600", "-S2500,1560", "-F:16", [SOFAfile '_xz_' num2str(Midx) '_Mmax=' num2str(M) '.png']);
      fputs(fid, [ "  printed figure 2/4: " SOFAfile '_xz_' num2str(Midx) '_Mmax=' num2str(M) ".png\n"]);

      % YZ view: looking along X axis (Y horizontal, Z vertical for Z-up).
      set(gca, 'CameraUpVectorMode', 'auto');
      view(90,0);
      if upAxis ~= 3
          if upAxis == 2 % Y-up: Z is horizontal bottom, Y is vertical left
              set(hy, 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'center');
              set(hz, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'center');
          else % X-up: Y is horizontal bottom, Z is vertical left
              set(hy, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'center');
              set(hz, 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'center');
          end
      end
      if upAxis == 2, set(gca, 'CameraUpVector', upVec); end
      print ('-dpng', "-r600", "-S2500,1560", "-F:16", [SOFAfile '_yz_' num2str(Midx) '_Mmax=' num2str(M) '.png']);
      fputs(fid, [ "  printed figure 3/4: " SOFAfile '_yz_' num2str(Midx) '_Mmax=' num2str(M) ".png\n"]);

      % XY view: restore the original view from SOFAplotGeometry.
      set(gca, 'CameraUpVectorMode', 'auto');
      view(v);
      if upAxis ~= 3
          % X is horizontal bottom, Y is vertical left
          set(hx, 'VerticalAlignment', 'top', 'HorizontalAlignment', 'center');
          set(hy, 'VerticalAlignment', 'bottom', 'HorizontalAlignment', 'center');
      end
      print ('-dpng', "-r600", "-S2500,1560", "-F:16", [SOFAfile '_xy_' num2str(Midx) '_Mmax=' num2str(M) '.png']);
      fputs(fid, [ "  printed figure 4/4: " SOFAfile '_xy_' num2str(Midx) '_Mmax=' num2str(M) ".png\n"]);

  end

  %% Epilogue: (un)comment if you want to:
  disp('DONE');
  fputs(fid, [ "\n### DONE ###\n"]);
  fclose(fid);
  toc; % timer

end