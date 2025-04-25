<div>
    @if(!empty($csvRows))
        <table class="min-w-full border border-gray-300 rounded">
            <thead>
                <tr>
                    @foreach ($csvRows[0] as $header)
                        <th class="px-2 py-1 bg-gray-100 border">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach (array_slice($csvRows, 1) as $row)
                    <tr>
                        @foreach ($row as $cell)
                            <td class="px-2 py-1 border">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-red-500 p-2">
            csv-file could not be read: ({{ $csvPath ?? 'unknown path' }})
        </div>
    @endif
</div>
