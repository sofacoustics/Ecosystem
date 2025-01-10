# git hooks

## post-merge

This hook will run composer update, npm install and npm run build. You must create a link to it in the .git/hooks/ directory. E.g.:

	cd .git/hooks && ln -s ../../git-hooks/post-merge post-merge
