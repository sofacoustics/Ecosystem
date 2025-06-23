<div>
    <div class="mb-8">
        {{-- <h2 class="text-lg font-bold mb-2">Dimensions ({{ urldecode(basename($csvPath ?? '')) }})</h2> --}}

        @if (!empty($csvRows))
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
                .sofa_dim.csv file could not be read: ({{ $csvPath ?? 'unknown path' }})
            </div>
        @endif
    </div>

    <div>
        {{-- <h2 class="text-lg font-bold mb-2">Properties ({{ urldecode(basename($csvPathProp ?? '')) }})</h2> --}}

        @if (!empty($csvRowsProp))
            <table class="min-w-full border border-gray-300 rounded">
                <thead>
                    <tr>
                        @foreach ($csvRowsProp[0] as $header)
                            <th class="px-2 py-1 bg-gray-100 border">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach (array_slice($csvRowsProp, 1) as $row)
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
                .sofa_prop.csv file could not be read: ({{ $csvPathProp ?? 'unknown path' }})
            </div>
        @endif
    </div>
</div>
