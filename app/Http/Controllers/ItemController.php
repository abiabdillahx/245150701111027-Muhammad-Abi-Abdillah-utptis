<?php
namespace App\Http\Controllers;

use App\Services\ItemService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        $items = $this->itemService->getAll();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function show($id)
    {
        $items = $this->itemService->getAll();
        $item = collect($items)->firstWhere('id', $id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item dengan ID ' . $id . ' tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0'
        ]);

        $items = $this->itemService->getAll();

        $newItem = [
            'id' => $this->itemService->generateId(),
            'nama' => $validated['nama'],
            'harga' => $validated['harga']
        ];

        $items[] = $newItem;
        $this->itemService->save($items);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'data' => $newItem
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric|min:0'
        ]);

        $item = $this->itemService->update($id, $validated);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID $id tidak ditemukan"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate',
            'data' => $item
        ]);
    }

    public function partialUpdate(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama' => 'sometimes|string',
            'harga' => 'sometimes|numeric|min:0'
        ]);

        $item = $this->itemService->partialUpdate($id, $validated);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID $id tidak ditemukan"
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil diupdate sebagian',
            'data' => $item
        ]);
    }

    public function destroy(int $id)
    {
        $items = $this->itemService->getAll();
        $index = collect($items)->search(fn($item) => $item['id'] === $id);

        if ($index === false) {
            return response()->json([
                'success' => false,
                'message' => "Item dengan ID $id tidak ditemukan"
            ], 404);
        }

        array_splice($items, $index, 1);
        $this->itemService->save($items);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus'
        ]);
    }
}