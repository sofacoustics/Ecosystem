#! /usr/bin/octave -q
printf ("%s", program_name ());
arg_list = argv ();
for i = 1:nargin
  printf (" %s", arg_list{i});
endfor
printf ("\n");

#fp = arg_list{1};
#addpath('/home/jw/syncthing/exchange/sofa/SOFAtoolbox');
path;
CreateFigures  hrtf_nh4.sofa
