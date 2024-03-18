pipename="/tmp/sonicom-octave-pipe"
system(['mkfifo ' pipename]); % öffnet eine named pipe namens myfifo
tmp=fopen(pipename,'r+'); % öffnet fürs schreiben
fid=fopen(pipename,'r'); % öffnet um zu lesen
%a=fscanf(fid,'%c',10); % lese die ersten 10 Zeichen ins a ein, dabei bleibt Octave hier stehen!


while ~feof( fid )
	%newLines{iLine,1} = fgetl( fid ) ;
	%iLine = iLine+1 ;
	str=fgetl(fid);
	printf("%s\n",str);
end

fclose(fid);
delete(pipename);
