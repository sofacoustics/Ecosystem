#!/bin/bash
#
# Build Octave
#
#  https://wiki.octave.org/Building
#  https://wiki.octave.org/Octave_for_Debian_systems#The_right_way
#
ov=${1:-8.4.0} # octave version
echo "Build Octave version $ov"

echo "Installing dependencies"
sudo update
sudo apt install gcc g++ autoconf automake bison dvipng epstool fig2dev flex gfortran gnuplot-x11 gperf gzip icoutils libarpack2-dev libbison-dev libopenblas-dev libcurl4-gnutls-dev libfftw3-dev libfltk1.3-dev libfontconfig1-dev libfreetype6-dev libgl1-mesa-dev libgl2ps-dev libglpk-dev libgraphicsmagick++1-dev libhdf5-dev liblapack-dev libosmesa6-dev libpcre3-dev libqhull-dev libqscintilla2-qt5-dev libqrupdate-dev libreadline-dev librsvg2-bin libsndfile1-dev libsuitesparse-dev libsundials-dev libtool libxft-dev make openjdk-8-jdk perl portaudio19-dev pstoedit qtbase5-dev qttools5-dev qttools5-dev-tools rapidjson-dev rsync tar texinfo texlive-latex-extra zlib1g-dev -y

echo "Downloading Octave $ov"
wget  https://ftpmirror.gnu.org/octave/octave-$ov.tar.gz && \
	tar -xzf octave-$ov.tar.gz && \
	cd octave-$ov

echo "Building, checking and installing Octave"
  mkdir .build                            && \
	cd    .build                            && \
	./../configure --prefix=$HOME/my_octave && \
	make -j2                                && \
  make check                              && \
	make install
