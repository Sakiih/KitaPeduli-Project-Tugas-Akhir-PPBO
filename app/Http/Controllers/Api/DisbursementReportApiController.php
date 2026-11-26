<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDisbursementReportRequest;
use App\Models\Campaign;
use App\Models\FundDisbursementReport;
use DomainException;
use Illuminate\Http\JsonResponse;

class DisbursementReportApiController extends Controller
{
    public function index(string $campaignId): JsonResponse
    {
        $campaign = ctype_digit($campaignId) ? Campaign::find((int) $campaignId) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        $reports = FundDisbursementReport::with('author:id,name')
            ->where('campaign_id', $campaign->id)
            ->orderByDesc('disbursed_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar laporan penyaluran dana berhasil diambil.',
            'data' => $reports,
            'meta' => [
                'total_disbursed_amount' => (float) $reports->sum('amount'),
                'available_balance' => FundDisbursementReport::availableBalanceForCampaign((int) $campaign->id),
                'count' => $reports->count(),
            ],
        ]);
    }

    public function store(StoreDisbursementReportRequest $request, string $campaignId): JsonResponse
    {
        $campaign = ctype_digit($campaignId) ? Campaign::find((int) $campaignId) : null;
        if (! $campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Kampanye tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        try {
            $report = FundDisbursementReport::recordForCampaign(
                (int) $campaign->id,
                $request->user(),
                $request->validated(),
                $request->file('document_image')
            );
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan penyaluran dana berhasil dicatat.',
            'data' => $report,
        ], 201);
    }

    public function update(StoreDisbursementReportRequest $request, string $id): JsonResponse
    {
        $report = ctype_digit($id) ? FundDisbursementReport::find((int) $id) : null;
        if (! $report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan penyaluran dana tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        try {
            $updated = FundDisbursementReport::updateRecord(
                $report,
                $request->validated(),
                $request->file('document_image')
            );
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan penyaluran dana berhasil diperbarui.',
            'data' => $updated,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $report = ctype_digit($id) ? FundDisbursementReport::find((int) $id) : null;
        if (! $report) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan penyaluran dana tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        FundDisbursementReport::deleteRecord($report);

        return response()->json([
            'success' => true,
            'message' => 'Laporan penyaluran dana berhasil dihapus.',
            'data' => null,
        ]);
    }
}
