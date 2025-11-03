<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['user', 'barangay'])
            ->latest('reported_at')
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,verified,resolved,false_alarm',
        ]);

        $report->update([
            'status' => $validated['status'],
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
        ]);

        return back()->with('success', 'Report status updated!');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return back()->with('success', 'Report successfully deleted');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,report_id',
            'action' => 'required|string|in:delete,mark_pending,mark_verified,mark_resolved,mark_false_alarm',
        ]);

        $reports = Report::whereIn('report_id', $validated['report_ids'])->get();

        switch ($validated['action']) {
            case 'delete':
                Report::whereIn('report_id', $validated['report_ids'])->delete();
                $message = count($reports) . ' report(s) deleted successfully.';
                break;
            case 'mark_pending':
            case 'mark_verified':
            case 'mark_resolved':
            case 'mark_false_alarm':
                $status = str_replace('mark_', '', $validated['action']);
                foreach ($reports as $report) {
                    $report->update([
                        'status' => $status,
                        'resolved_at' => $status === 'resolved' ? now() : null,
                    ]);
                }
                $message = count($reports) . ' report(s) marked as ' . ucfirst(str_replace('_', ' ', $status)) . '.';
                break;
            default:
                return back()->with('error', 'Invalid action.');
        }
        return back()->with('success', $message);
    }
}
