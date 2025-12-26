% SofaProperties - Function to load SOFA files, create and save visualizing 1 figure
% #Author: Michael Mihocic: SofaProperties extracted and stored (15.04.2025)
% #Author: Michael Mihocic: Modified for Octave compatibility (25.04.2025)
% #Author: Michael Mihocic: Dimensions displayed, 2 tables created (23.06.2025)
% #Author: Piotr Majdak: added path to shared functions, moved the call to SOFA Properties to shared (27.12.2025)
%
% Copyright (C) Acoustics Research Institute - Austrian Academy of Sciences
% Licensed under the EUPL, Version 1.2 or - as soon they will be approved by the European Commission - subsequent versions of the EUPL (the "License")
% You may not use this work except in compliance with the License.
% You may obtain a copy of the License at: https://joinup.ec.europa.eu/software/page/eupl
% Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
% See the License for the specific language governing permissions and limitations under the License.

function SofaProperties(SOFAfile)
	addpath('../shared'); % add the path to shared functions
	isoctave = exist('OCTAVE_VERSION', 'builtin') ~= 0;

	logfile="SofaProperties.log";
	fid = fopen(logfile, "w");
	s = pwd;
	disp(["pwd = " s]);

	%% Prologue: (un)comment here if you want to:
	tic; % timer
	SOFAstart; % remove this optionally
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
	if isoctave; fputs(fid, ["Successfully saved SOFA details to csv files\n"]); end

	%% Epilogue: (un)comment if you want to:
	disp('DONE');
	if isoctave; fputs(fid, "Script finished.\n"); fclose(fid); end % Close log file at the end
	toc; % timer
end % End of function SofaProperties


