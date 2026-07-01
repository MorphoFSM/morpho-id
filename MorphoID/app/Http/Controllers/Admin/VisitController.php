<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitsExport;

class VisitController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new VisitsExport, 'Visitor_Login_Records_' . date('Y_m_d_His') . '.xlsx');
    }

    // Tambah (Request $request) supaya sistem boleh tangkap arahan filter dari HTML
    public function index(Request $request)
    {
        $query = LoginLog::query();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('role') && strtolower($request->role) !== 'semua' && strtolower($request->role) !== 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('userid', 'ilike', "%{$search}%")
                  ->orWhere('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $query->orderBy('created_at', 'desc');

        // Total visitor count (regardless of pagination, but respecting filters)
        $visitor_count = (clone $query)->count();

        // Pagination
        $perPage = $request->input('per_page', 10);
        if ($perPage === 'all') {
            $logs = $query->get();
        } else {
            $logs = $query->paginate($perPage)->appends($request->all());
        }

        if (view()->exists('admin.visits')) {
            return view('admin.visits', compact('logs', 'visitor_count'));
        } else {
            dd('CCTV: Fail admin/visits.blade.php tak dijumpai dalam folder views/admin!');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:login_logs,id',
        ]);

        LoginLog::whereIn('id', $request->ids)->delete();

        return back()->with('success', count($request->ids) . ' logs deleted successfully.');
    }

    public function chartData(Request $request)
    {
        $filter = $request->input('filter', 'day');
        $query = LoginLog::query();
        $labels = [];
        $data = [];

        if ($filter == 'day') {
            // Group by hour for today
            $today = Carbon::today();
            $logs = $query->whereDate('created_at', $today)->get();
            for ($i = 0; $i < 24; $i++) {
                $labels[] = sprintf('%02d:00', $i);
                $data[] = $logs->filter(function($log) use ($i) {
                    return Carbon::parse($log->created_at)->hour == $i;
                })->count();
            }
        } elseif ($filter == 'week') {
            // Group by day for last 7 days
            $start = Carbon::now()->subDays(6)->startOfDay();
            $logs = $query->where('created_at', '>=', $start)->get();
            for ($i = 0; $i < 7; $i++) {
                $date = $start->copy()->addDays($i);
                $labels[] = $date->format('D, M d');
                $data[] = $logs->filter(function($log) use ($date) {
                    return Carbon::parse($log->created_at)->isSameDay($date);
                })->count();
            }
        } elseif ($filter == 'year') {
            // Group by month for current year
            $year = Carbon::now()->year;
            $logs = $query->whereYear('created_at', $year)->get();
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->format('M');
                $data[] = $logs->filter(function($log) use ($i) {
                    return Carbon::parse($log->created_at)->month == $i;
                })->count();
            }
        } else {
            // All time: group by Year-Month
            $logs = $query->orderBy('created_at', 'asc')->get();
            if ($logs->isEmpty()) {
                $labels = [Carbon::now()->format('M Y')];
                $data = [0];
            } else {
                $grouped = $logs->groupBy(function($log) {
                    return Carbon::parse($log->created_at)->format('M Y');
                });
                foreach ($grouped as $key => $group) {
                    $labels[] = $key;
                    $data[] = $group->count();
                }
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
