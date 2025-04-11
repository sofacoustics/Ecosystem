{{ $datafile->name }}
(Dataset: <a href="{{ route('datasets.show', $datafile->dataset) }}">{{ $datafile->dataset->name }}</a>,
Database: <a href="{{ route('databases.show', $datafile->dataset->database) }}">{{ $datafile->dataset->database->title }}</a>)
