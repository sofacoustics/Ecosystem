%
%   Test pipe in Octave
% 
%   Usage: octave-cli octave_pipe.m
%
%   Exit by sending the word 'exit' to the pipe.
%
arg_list = argv ();
printf("n args = %d\n", nargin);
if(nargin > 0)
    pipename=arg_list{1};
else
    pipename="/var/run/sonicom-octave-pipe";
endif
for i = 1:nargin
      printf (" %s", arg_list{i});
endfor
logfile="/var/log/sonicom-octave-pipe.log"
fidlogfile = fopen (logfile, "w");

 




printf("pipename = %s\n", pipename);

% remove pipe file if it exists
if(exist(pipename)==2)
    printf("Removing existing %s pipe file\n", pipename);
    delete(pipename);
endif

printf("mkfifo\n");
system(['mkfifo -m 666 ' pipename]); % öffnet eine named pipe namens myfifo
printf("fopen\n");
fid=fopen(pipename,'r'); % öffnet um zu lesen
printf("Listening to pipe file %s\n", pipename);
stat(pipename);


%s=fgets(fid);
%printf("%s\n", s);
%while strcmp(s,"exit")==0
%   s=fgets(fid);
%   printf("%s\n", s);
%end
%while true
%   % do something with s
%   s=fgets(fid);
%   printf("%s\n", s);
%   if(strcmp(s,"q")==1)
%       break;
%   endif
%end
%str=fgetl(fid);
%printf("str = %s\n", str);
%while ischar(str)
%   printf("%s\n",str);
%   if (strcmp(str, "exit") == 1)
%       printf("exit keyword found. Breaking!\n");
%       break;
%   endif
%   if(~feof(fid))
%       printf("End of file reached. Breaking\n");
%       break;  
%   endif
%   printf("Waiting for new input\n");
%   str=fgetl(fid);
%end

%str=fscanf(fid,"%c",5);
%printf("str=%s\n",str);

%while true
%   pause (5);
%   printf("Processing...\n");
%   str=fgetl(fid);
%   if(str!=-1)
%       printf("A str='%s', len=%d\n", str, length(str));
%   else
%       printf("B str='%s', len=%d\n", str, length(str));
%   endif
%end

%while true
%       (ischar (s = fgets (fid)))
%     fputs (stdout, s);
%endwhile

%(! feof (fid) )
while true
      fname=""
      arguments=""
      str = fgetl(fid);
      printf("'%s'\n", str);
      len = length(str);
      n = index(str, ' ');
      if(n > 1)
          fname = strtrim(substr(str, 1, n)); % function name
          printf("function name = '%s'\n", fname);
          arguments = strtrim(substr(str, n));
          printf("arguments = '%s'\n", arguments);
      endif

    fputs(fidlogfile, [fname, " ", arguments]);

      if(length(fname)>0)
        %jw:todo run code here
        feval(fname, arguments);
      endif

      
        if(strncmp(str,"exit",4)==1)
            printf("Exiting\n");
            break;
        endif
        if(feof(fid)==1)
            printf("EOF reached. Reopening pipe\n");
            fid=fopen(pipename,'r'); % öffnet um zu lesen
            printf("Listening to pipe file %s\n", pipename);
        endif
endwhile

fclose (fidlogfile);
fclose(fid);
printf("Deleting %s\n", pipename);
delete(pipename);

