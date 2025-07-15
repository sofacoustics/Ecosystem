# Octave

You can build octave 10.2.0 using the `build-octave --dependencies --source --build --build-gl2ps --configure` script. This was necessary to fix colorbars in the HRTFGeneral.m script which weren't being drawn. The error hinting at a problem was:

    warning: GL2PS must be compiled with PNG support in order to embed images in SVG streams

## Files

octaverc        Contains all the initialisation stuff for the SOFAtoolbox and required packages. If you need new packages, please put them in here and run `build-octave --configure` again.
build-gl2ps     The script which builds the GL2PS library with the necessary PNG flag required for drawing colorbars.
build-octave    The script which builds octave using the above GL2PS library version.
