% Setup the Octave environment to run SOFA
%
% Note that installing netcdf didn't work when running the snap ocatve package

sofapath = '/home/jw/syncthing/exchange/sofa/SOFAtoolbox';

disp (["Adding sofapath " sofapath " to path"]);
addpath(sofapath);
path();

disp ("Installing netcdf");
pkg install netcdf -forge
