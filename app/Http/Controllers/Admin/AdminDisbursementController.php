<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisbursementReportRequest;
use App\Models\Campaign;
use App\Models\FundDisbursementReport;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDisbursementController extends Controller
{
    public function index(Request $request): View
    {
        $campaigns = Campaign::orderBy('title')->get();
        $query = FundDisbursementReport::with(['campaign', 'author']);

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', (int) $request->query('campaign_id'));
        }

        $reports = $query->orderByDesc('disbursed_at')->paginate(10)->withQueryString();

        return view('admin.disbursements.index', compact('reports', 'campaigns'));
    }

    public function store(StoreDisbursementReportRequest $request): RedirectResponse
    {
        $request->validate([
            'campaign_id' => ['required', 'integer', 'exists:campaigns,id'],
        ]);

        try {
            FundDisbursementReport::recordForCampaign(
                (int) $request->input('campaign_id'),
                $request->user(),
                $request->validated(),
                $request->file('document_image')
            );
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Catatan laporan penyaluran dana berhasil disimpan.');
    }

    public function update(StoreDisbursementReportRequest $request, FundDisbursementReport $report): RedirectResponse
    {
        try {
            FundDisbursementReport::updateRecord(
                $report,
                $request->validated(),
                $request->file('document_image')
            );
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Laporan penyaluran dana berhasil diperbarui.');
    }

    public function destroy(FundDisbursementReport $report): RedirectResponse
    {
        FundDisbursementReport::deleteRecord($report);

        return back()->with('success', 'Laporan penyaluran dana berhasil dihapus.');
    }
}
