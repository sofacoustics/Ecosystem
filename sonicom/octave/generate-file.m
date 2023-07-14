% generate-file.m
fname = args{1};
content = 'This is the test content';
save(fname, 'content');
