<?php

namespace App\Traits;

use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Pagination\LengthAwarePaginator;

trait ParsesCsv
{
    public function getCsvPaginator($filePath, $perPage = 15)
    {
        if (!file_exists($filePath)) {
            abort(404, 'CSV File not found.');
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); 

        $currentPage = $this->getPage(); // Works if the host component uses WithPagination
        $offset = ($currentPage - 1) * $perPage;

        $stmt = Statement::create()->offset($offset)->limit($perPage);
        $records = $stmt->process($csv);

        return [
            'headers' => $csv->getHeader(),
            'rows' => new LengthAwarePaginator(
                iterator_to_array($records, true),
                count($csv),
                $perPage,
                $currentPage,
                ['path' => url()->current()]
            )
        ];
    }
}
