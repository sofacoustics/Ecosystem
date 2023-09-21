#! /usr/bin/octave -qW
printf ("%s", program_name ());
arg_list = argv ();
if(nargin>0)
	for i = 1:nargin
		printf (" %s", arg_list{i});
	endfor
	printf ("\n");
else
	printf ("\n");
  disp("You must specify a SOFA file!");
  exit
end

fp = arg_list{1};
# Note that SOFAtoolbox is added to path in .octaverc
if(strcmp(fp,"")!=1)
	CreateFigures(fp);
else
end
