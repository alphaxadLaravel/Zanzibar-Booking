<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlightBooking;
use App\Models\FlightClick;
use App\Models\FlightSearch;
use App\Services\Flights\FlightAnalyticsExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlightAnalyticsController extends Controller
{
    public function __construct(
        protected FlightAnalyticsExportService $exportService,
    ) {}

    public function index()
    {
        $stats = [
            'total_searches' => FlightSearch::count(),
            'today_searches' => FlightSearch::whereDate('created_at', today())->count(),
            'total_bookings' => FlightBooking::count(),
            'confirmed_bookings' => FlightBooking::where('status', 'confirmed')->count(),
            'pending_bookings' => FlightBooking::where('status', 'pending')->count(),
            'today_bookings' => FlightBooking::whereDate('created_at', today())->count(),
        ];

        $revenue = [
            'customer_paid' => (float) FlightBooking::sum('total_price'),
            'duffel_cost' => (float) FlightBooking::sum(DB::raw('COALESCE(supplier_cost, 0)')),
            'zanzibar_margin' => (float) FlightBooking::sum(DB::raw('COALESCE(markup_amount, 0)')),
            'confirmed_customer_paid' => (float) FlightBooking::where('status', 'confirmed')->sum('total_price'),
            'confirmed_duffel_cost' => (float) FlightBooking::where('status', 'confirmed')->sum(DB::raw('COALESCE(supplier_cost, 0)')),
            'confirmed_margin' => (float) FlightBooking::where('status', 'confirmed')->sum(DB::raw('COALESCE(markup_amount, 0)')),
        ];

        $searches = FlightSearch::query()
            ->with('user')
            ->latest()
            ->paginate(20, ['*'], 'searches_page');

        $bookings = FlightBooking::query()
            ->with(['user', 'payment'])
            ->latest()
            ->paginate(20, ['*'], 'bookings_page');

        $dailySearches = $this->dailySearchChart(30);
        $topRoutes = $this->topRoutes(8);

        return view('admin.pages.flights.analytics', compact(
            'stats',
            'revenue',
            'searches',
            'bookings',
            'dailySearches',
            'topRoutes',
        ));
    }

    public function showSearch(FlightSearch $search)
    {
        $search->load('user', 'clicks');

        return view('admin.pages.flights.search-details', compact('search'));
    }

    public function export(Request $request, string $type)
    {
        $filters = [
            'sort' => 'created_at',
            'direction' => 'desc',
        ];
        $dataset = $request->input('dataset', 'searches');

        return $this->exportService->export($type, $dataset, $filters);
    }

    public function bulkDeleteSearches(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:flight_searches,id'],
        ]);

        $count = FlightSearch::whereIn('id', $request->input('ids'))->delete();

        return back()->with('success', "{$count} search record(s) deleted.");
    }

    public function bulkDeleteClicks(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:flight_clicks,id'],
        ]);

        $count = FlightClick::whereIn('id', $request->input('ids'))->delete();

        return back()->with('success', "{$count} click record(s) deleted.");
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    protected function dailySearchChart(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $raw = FlightSearch::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $start)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $labels[] = $date->format('M d');
            $values[] = (int) ($raw[$date->toDateString()] ?? 0);
        }

        return compact('labels', 'values');
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    protected function topRoutes(int $limit = 8): array
    {
        $rows = FlightSearch::query()
            ->select('origin_code', 'destination_code', DB::raw('COUNT(*) as total'))
            ->groupBy('origin_code', 'destination_code')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return [
            'labels' => $rows->map(fn ($row) => $row->origin_code . ' → ' . $row->destination_code)->all(),
            'values' => $rows->pluck('total')->map(fn ($v) => (int) $v)->all(),
        ];
    }
}
