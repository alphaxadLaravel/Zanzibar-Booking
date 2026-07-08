<?php

namespace App\Services\Flights;

use App\Repositories\FlightClickRepository;
use App\Repositories\FlightSearchRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FlightAnalyticsExportService
{
    public function __construct(
        protected FlightSearchRepository $searches,
        protected FlightClickRepository $clicks,
    ) {}

    public function export(string $type, string $dataset, array $filters = [])
    {
        $rows = $dataset === 'clicks'
            ? $this->clicks->query($filters)->get()
            : $this->searches->query($filters)->get();

        return match ($type) {
            'csv' => $this->csvResponse($rows, $dataset),
            'excel' => $this->excelResponse($rows, $dataset),
            'pdf' => $this->pdfResponse($rows, $dataset),
            default => abort(404),
        };
    }

    protected function csvResponse($rows, string $dataset): StreamedResponse
    {
        $filename = "flight_{$dataset}_" . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($rows, $dataset) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $this->headers($dataset));

            foreach ($rows as $row) {
                fputcsv($handle, $this->mapRow($row, $dataset));
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function excelResponse($rows, string $dataset): StreamedResponse
    {
        $filename = "flight_{$dataset}_" . now()->format('Y-m-d_His') . '.xls';

        return response()->streamDownload(function () use ($rows, $dataset) {
            echo '<table border="1"><tr>';
            foreach ($this->headers($dataset) as $header) {
                echo '<th>' . e($header) . '</th>';
            }
            echo '</tr>';

            foreach ($rows as $row) {
                echo '<tr>';
                foreach ($this->mapRow($row, $dataset) as $cell) {
                    echo '<td>' . e((string) $cell) . '</td>';
                }
                echo '</tr>';
            }

            echo '</table>';
        }, $filename, ['Content-Type' => 'application/vnd.ms-excel']);
    }

    protected function pdfResponse($rows, string $dataset)
    {
        return response()->view('admin.pages.flights.export-pdf', [
            'rows' => $rows,
            'dataset' => $dataset,
            'headers' => $this->headers($dataset),
            'generatedAt' => now(),
        ]);
    }

    protected function headers(string $dataset): array
    {
        if ($dataset === 'clicks') {
            return ['ID', 'Airline', 'Flight Number', 'Origin', 'Destination', 'Price', 'Currency', 'Affiliate', 'User', 'Clicked At', 'Country', 'Device'];
        }

        return ['ID', 'Origin', 'Destination', 'Departure', 'Return', 'Adults', 'Children', 'Infants', 'Cabin', 'User', 'Date', 'Country', 'Device'];
    }

    protected function mapRow($row, string $dataset): array
    {
        if ($dataset === 'clicks') {
            return [
                $row->id,
                $row->airline,
                $row->flight_number,
                $row->origin,
                $row->destination,
                $row->price,
                $row->currency,
                $row->affiliate_name,
                $row->user?->email ?? 'Guest',
                $row->clicked_at?->format('Y-m-d H:i'),
                $row->country,
                $row->device,
            ];
        }

        return [
            $row->id,
            $row->origin_code,
            $row->destination_code,
            $row->departure_date?->format('Y-m-d'),
            $row->return_date?->format('Y-m-d'),
            $row->adults,
            $row->children,
            $row->infants,
            $row->travel_class,
            $row->user?->email ?? 'Guest',
            $row->created_at?->format('Y-m-d H:i'),
            $row->country,
            $row->device,
        ];
    }
}
