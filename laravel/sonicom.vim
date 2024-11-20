let SessionLoad = 1
if &cp | set nocp | endif
let s:so_save = &g:so | let s:siso_save = &g:siso | setg so=0 siso=0 | setl so=-1 siso=-1
let v:this_session=expand("<sfile>:p")
silent only
silent tabonly
cd ~/git/isf-sonicom-laravel/laravel
if expand('%') == '' && !&modified && line('$') <= 1 && getline(1) == ''
  let s:wipebuf = bufnr('%')
endif
let s:shortmess_save = &shortmess
if &shortmess =~ 'A'
  set shortmess=aoOA
else
  set shortmess=aoO
endif
badd +1 database/migrations/2024_10_14_134908_create_radardataset_table.php
badd +16 database/migrations/2024_10_14_135323_create_radarworkspace_table.php
badd +1 database/migrations/2023_09_05_084635_create_datasets_table.php
badd +64 app/Http/Controllers/DatabaseController.php
badd +267 ~/git/isf-sonicom-laravel/README.md
badd +18 database/migrations/2024_10_14_135353_create_radardataset_table.php
badd +26 app/Data/RadardatasetData.php
badd +7 app/Data/RadarresourcetypeData.php
badd +16 database/migrations/2024_10_14_141607_create_radardatasetresourcetypes_table.php
badd +9 database/seeders/RadardatasetresourcetypeSeeder.php
badd +36 database/seeders/DatabaseSeeder.php
badd +23 database/migrations/2024_10_14_142401_create_radardatasetresourcetypes_table.php
badd +1 app/Http/Resources/TestResource.php
badd +6 app/Http/Resources/DatabaseResource.php
badd +1 app/Data/Radar/DatasetData.php
badd +26 app/Data/RadarcreatorData.php
badd +7 app/Models/Radardatasetresourcetype.php
badd +1 ~/Downloads/RADAR_DATASET_DESCRIPTIVE_METADATA(6)
badd +8 ~/Downloads/RADAR_DATASET_DESCRIPTIVE_METADATA(8)
badd +22 app/Models/Radardataset.php
badd +6 database/migrations/2024_10_15_095532_create_radardatasets_table.php
badd +17 database/seeders/RadardatasetSeeder.php
badd +40 app/Models/Database.php
badd +1 database/migrations
badd +19 database/migrations/2023_08_04_084556_create_databases_table.php
badd +1 resources/views/databases/index.blade.php
badd +1 app/Data
badd +12 app/Data/RadardatasetsubjectareaData.php
badd +23 app/Data/RadardatasetresourcetypeData.php
badd +9 app/Models/Radardatasetsubjectarea.php
badd +1 \*\*Radardatasetresou\*
badd +18 database/migrations/2024_10_16_071216_create_radardatasetsubjectareas_table.php
badd +18 database/seeders/RadardatasetsubjectareaSeeder.php
argglobal
%argdel
set stal=2
tabnew +setlocal\ bufhidden=wipe
tabnew +setlocal\ bufhidden=wipe
tabrewind
edit resources/views/databases/index.blade.php
argglobal
balt app/Data/RadardatasetData.php
setlocal fdm=manual
setlocal fde=0
setlocal fmr={{{,}}}
setlocal fdi=#
setlocal fdl=0
setlocal fml=1
setlocal fdn=20
setlocal fen
silent! normal! zE
let &fdl = &fdl
let s:l = 61 - ((39 * winheight(0) + 23) / 46)
if s:l < 1 | let s:l = 1 | endif
keepjumps exe s:l
normal! zt
keepjumps 61
normal! 013|
tabnext
edit app/Http/Controllers/DatabaseController.php
argglobal
setlocal fdm=manual
setlocal fde=0
setlocal fmr={{{,}}}
setlocal fdi=#
setlocal fdl=0
setlocal fml=1
setlocal fdn=20
setlocal fen
silent! normal! zE
let &fdl = &fdl
let s:l = 91 - ((38 * winheight(0) + 23) / 46)
if s:l < 1 | let s:l = 1 | endif
keepjumps exe s:l
normal! zt
keepjumps 91
normal! 010|
tabnext
edit app/Models/Radardataset.php
argglobal
setlocal fdm=manual
setlocal fde=0
setlocal fmr={{{,}}}
setlocal fdi=#
setlocal fdl=0
setlocal fml=1
setlocal fdn=20
setlocal fen
silent! normal! zE
let &fdl = &fdl
let s:l = 31 - ((30 * winheight(0) + 23) / 46)
if s:l < 1 | let s:l = 1 | endif
keepjumps exe s:l
normal! zt
keepjumps 31
normal! 043|
tabnext 3
set stal=1
if exists('s:wipebuf') && len(win_findbuf(s:wipebuf)) == 0
  silent exe 'bwipe ' . s:wipebuf
endif
unlet! s:wipebuf
set winheight=1 winwidth=20
let &shortmess = s:shortmess_save
let s:sx = expand("<sfile>:p:r")."x.vim"
if filereadable(s:sx)
  exe "source " . fnameescape(s:sx)
endif
let &g:so = s:so_save | let &g:siso = s:siso_save
nohlsearch
doautoall SessionLoadPost
unlet SessionLoad
" vim: set ft=vim :
