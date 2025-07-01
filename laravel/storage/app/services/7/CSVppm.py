# /// script
# requires-python = "==3.11.*"
# dependencies = [
#   "pybezierppm@git+https://github.com/Any2HRTF/PPM@cd8c224578808f5a729102068ceeed9d9ebe07dd", # pinning the commit we published the paper with and the functonality is assured
# ]
# ///

import argparse
from bezierppm import BezierPPM
def main(args):
    ppm = BezierPPM(from_csv_file=args.input)
    ppm.export_stl(f'{args.input}_1.stl')

if __name__ == "__main__":
    parser = argparse.ArgumentParser()

    parser.add_argument("--input", type=str, help="Path to the csv file with the PPM parameters.")

    main(parser.parse_args())

