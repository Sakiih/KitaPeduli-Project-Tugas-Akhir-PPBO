<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Services\DonationService;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DonationReactionApiController extends Controller
{
    public function __construct(
        private readonly DonationService $donationService
    ) {}

    public function store(Request $request, string $id): JsonResponse
    {
        $donation = ctype_digit($id) ? Donation::find((int) $id) : null;
        if (! $donation) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        try {
            $result = $this->donationService->giveAmen($donation, $request->user());
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Aamiin berhasil diberikan.',
            'data' => [
                'donation_id' => $donation->id,
                'amen_count' => $result['amen_count'],
            ],
        ], 201);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $donation = ctype_digit($id) ? Donation::find((int) $id) : null;
        if (! $donation) {
            return response()->json([
                'success' => false,
                'message' => 'Data donasi tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        try {
            $result = $this->donationService->removeAmen($donation, $request->user());
        } catch (DomainException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Aamiin berhasil dibatalkan.',
            'data' => [
                'donation_id' => $donation->id,
                'amen_count' => $result['amen_count'],
            ],
        ]);
    }
}
