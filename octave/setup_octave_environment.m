% Setup the Octave environment to run SOFA
%
% Note that installing netcdf didn't work when running the snap ocatve package

file_path = fileparts(mfilename('fullpath'));

disp (["Adding script directory " file_path " to path"]);
addpath(file_path);

sofapath = [file_path '/SOFAtoolbox'];
disp (["Adding sofapath " sofapath " to path"]);
addpath(sofapath);

path();

disp ("Installing netcdf");
pkg install netcdf -forge
