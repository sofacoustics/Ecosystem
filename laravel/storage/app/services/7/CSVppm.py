from bezierppm import BezierPPM
    # path to the CSV file needs to be defined
ppm = BezierPPM(from_csv='path/to/file.csv')
    # path of the output file needs to be defined
ppm.export_stl('path/to/file_1.stl')
