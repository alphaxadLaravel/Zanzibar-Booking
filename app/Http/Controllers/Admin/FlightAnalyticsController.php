<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightClick;
use App\Models\FlightSearch;
use App\Repositories\FlightClickRepository;
use App\Repositories\FlightSearchRepository;
use App\Services\Flights\FlightAnalyticsExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightAnalyticsController extends Controller
{
    public function __construct(
        protected FlightSearchRepository $searches,
        protected FlightClickRepository $clicks,
        protected FlightAnalyticsExportService $exportService,
    ) {}

    public function index(Request $request)
    {
        $filters = $this->filtersFromRequest($request);

        $stats = $this->clicks->dashboardStats($filters);
        $searches = $this->searches->paginate($filters, 15);
        $clickRows = $this->clicks->paginate($filters, 15);

        $dailySearches = $this->clicks->dailySeries('flight_searches', 'created_at', $filters)->pluck('total', 'day');
        $dailyClicks = $this->clicks->dailySeries('flight_clicks', 'clicked_at', $filters)->pluck('total', 'day');
        $topAirlines = $this->clicks->topGrouped('airline', $filters);
        $topDestinations = $this->searches->query($filters)
            ->select('destination_code', DB::raw('COUNT(*) as total'))
            ->groupBy('destination_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $topOrigins = $this->searches->query($filters)
            ->select('origin_code', DB::raw('COUNT(*) as total'))
            ->groupBy('origin_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        $monthlyTrends = $this->clicks->monthlyTrends($filters);

        $countries = FlightSearch::query()
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $airlines = FlightClick::query()
            ->whereNotNull('airline')
            ->distinct()
            ->orderBy('airline')
            ->pluck('airline');

        return view('admin.pages.flights.analytics', compact(
            'stats',
            'searches',
            'clickRows',
            'dailySearches',
            'dailyClicks',
            'topAirlines',
            'topOrigins',
            'topDestinations',
            'monthlyTrends',
            'countries',
            'airlines',
            'filters',
        ));
    }

    public function showSearch(FlightSearch $search)
    {
        $search->load('user', 'clicks');

        return view('admin.pages.flights.search-details', compact('search'));
    }

    public function export(Request $request, string $type)
    {
        $filters = $this->filtersFromRequest($request);
        $dataset = $request->input('dataset', 'searches');

        return $this->exportService->export($type, $dataset, $filters);
    }

    public function bulkDeleteSearches(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:flight_searches,id'],
        ]);

        $count = $this->searches->deleteByIds($request->input('ids'));

        return back()->with('success', "{$count} search record(s) deleted.");
    }

    public function bulkDeleteClicks(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:flight_clicks,id'],
        ]);

        $count = $this->clicks->deleteByIds($request->input('ids'));

        return back()->with('success', "{$count} click record(s) deleted.");
    }

    protected function filtersFromRequest(Request $request): array
    {
        return [
            'search' => $request->input('search'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'country' => $request->input('country'),
            'airline' => $request->input('airline'),
            'origin' => $request->input('origin'),
            'destination' => $request->input('destination'),
            'user_type' => $request->input('user_type'),
            'sort' => $request->input('sort', 'created_at'),
            'direction' => $request->input('direction', 'desc'),
        ];
    }
}
