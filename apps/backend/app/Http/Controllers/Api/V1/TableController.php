<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Table\StoreTableRequest;
use App\Http\Requests\Table\UpdateTableRequest;
use App\Http\Resources\TableResource;
use App\Models\Restaurant;
use App\Models\Table;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TableController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodeService,
    ) {}

    public function index(Restaurant $restaurant): JsonResponse
    {
        $this->authorize('viewAny', [Table::class, $restaurant]);

        $tables = $restaurant->tables()->with('restaurant')->get();

        return response()->json([
            'data' => TableResource::collection($tables),
        ]);
    }

    public function store(StoreTableRequest $request, Restaurant $restaurant): JsonResponse
    {
        $this->authorize('create', [Table::class, $restaurant]);

        $table = $restaurant->tables()->create($request->validated());

        // Generate QR code
        $menuUrl = url("/menu/{$restaurant->slug}/{$table->uuid}");
        $qrPath = $this->qrCodeService->generate($menuUrl, $table->uuid);
        $table->update(['qr_code_path' => $qrPath]);

        $table->load('restaurant');

        return response()->json([
            'data' => new TableResource($table),
        ], 201);
    }

    public function show(Restaurant $restaurant, Table $table): JsonResponse
    {
        $this->authorize('view', $table);

        $table->load('restaurant');

        return response()->json([
            'data' => new TableResource($table),
        ]);
    }

    public function update(UpdateTableRequest $request, Restaurant $restaurant, Table $table): JsonResponse
    {
        $this->authorize('update', $table);

        $table->update($request->validated());

        $table->load('restaurant');

        return response()->json([
            'data' => new TableResource($table),
        ]);
    }

    public function destroy(Restaurant $restaurant, Table $table): JsonResponse
    {
        $this->authorize('delete', $table);

        if ($table->qr_code_path) {
            $this->qrCodeService->delete($table->qr_code_path);
        }

        $table->delete();

        return response()->json(null, 204);
    }

    public function downloadQr(Restaurant $restaurant, Table $table): StreamedResponse|JsonResponse
    {
        $this->authorize('view', $table);

        if (! $table->qr_code_path || ! Storage::disk('public')->exists($table->qr_code_path)) {
            return response()->json(['message' => 'QR code not found.'], 404);
        }

        return Storage::disk('public')->download($table->qr_code_path, "qr-{$table->label}.png");
    }
}
