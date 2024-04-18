# Octave

## Installing octave 6

	apt install octave

Error: "error: package netcdf is not installed"
Solution: 'apt install liboctave-dev' and 'pkg install -forge netcdf'

Error: "configure: error: nc-config not found"
Solution: 'apt install libnetcdf-dev'

## Running the octave pipe

	octave-cli octave_pipe.m
