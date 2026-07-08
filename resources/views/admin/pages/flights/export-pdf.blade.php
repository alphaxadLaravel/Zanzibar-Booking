<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight {{ ucfirst($dataset) }} Export</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
        h1 { font-size: 18px; }
    </style>
</head>
<body onload="window.print()">
    <h1>Flight {{ ucfirst($dataset) }} Report</h1>
    <p>Generated: {{ $generatedAt->format('Y-m-d H:i') }}</p>
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                @if($dataset === 'clicks')
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->airline }}</td>
                    <td>{{ $row->flight_number }}</td>
                    <td>{{ $row->origin }}</td>
                    <td>{{ $row->destination }}</td>
                    <td>{{ $row->price }}</td>
                    <td>{{ $row->currency }}</td>
                    <td>{{ $row->affiliate_name }}</td>
                    <td>{{ $row->user?->email ?? 'Guest' }}</td>
                    <td>{{ $row->clicked_at?->format('Y-m-d H:i') }}</td>
                    <td>{{ $row->country }}</td>
                    <td>{{ $row->device }}</td>
                @else
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->origin_code }}</td>
                    <td>{{ $row->destination_code }}</td>
                    <td>{{ $row->departure_date?->format('Y-m-d') }}</td>
                    <td>{{ $row->return_date?->format('Y-m-d') }}</td>
                    <td>{{ $row->adults }}</td>
                    <td>{{ $row->children }}</td>
                    <td>{{ $row->infants }}</td>
                    <td>{{ $row->travel_class }}</td>
                    <td>{{ $row->user?->email ?? 'Guest' }}</td>
                    <td>{{ $row->created_at?->format('Y-m-d H:i') }}</td>
                    <td>{{ $row->country }}</td>
                    <td>{{ $row->device }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
