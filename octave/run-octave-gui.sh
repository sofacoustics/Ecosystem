# Run the octave gui
# For stable results, do not run as 'www-data', but rather as 'sonicom'
echo "$(realpath $0) $@"
script=$1
shift 1
XDG_RUNTIME_DIR="/tmp/runtime-sonicom" DISPLAY=:1 /usr/bin/octave --gui "$script" "$@"
