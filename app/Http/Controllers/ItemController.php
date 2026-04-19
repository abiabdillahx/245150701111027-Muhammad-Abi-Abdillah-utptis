<?php
namespace App\Http\Controllers;

use App\Services\ItemService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ItemController extends Controller
{
    protected ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    #[OA\Get(
        path: "/api/items",
        summary: "Menampilkan semua item",
        tags: ["Items"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "nama", type: "string", example: "Laptop"),
                                new OA\Property(property: "harga", type: "number", example: 15000000),
                            ]
                        ))
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $items = $this->itemService->getAll();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    #[OA\Get(
        path: "/api/items/{id}",
        summary: "Menampilkan item berdasarkan ID",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "nama", type: "string", example: "Laptop"),
                            new OA\Property(property: "harga", type: "number", example: 15000000),
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Item tidak ditemukan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Item dengan ID 1 tidak ditemukan"),
                    ]
                )
            )
        ]
    )]
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

    #[OA\Post(
        path: "/api/items",
        summary: "Menambahkan item baru",
        tags: ["Items"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama", "harga"],
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Monitor"),
                    new OA\Property(property: "harga", type: "number", example: 3000000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Item berhasil ditambahkan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Item berhasil ditambahkan"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 4),
                            new OA\Property(property: "nama", type: "string", example: "Monitor"),
                            new OA\Property(property: "harga", type: "number", example: 3000000),
                        ])
                    ]
                )
            )
        ]
    )]
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

    #[OA\Put(
        path: "/api/items/{id}",
        summary: "Mengupdate seluruh data item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama", "harga"],
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Laptop Pro"),
                    new OA\Property(property: "harga", type: "number", example: 20000000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Item berhasil diupdate",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Item berhasil diupdate"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "nama", type: "string", example: "Laptop Pro"),
                            new OA\Property(property: "harga", type: "number", example: 20000000),
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Item tidak ditemukan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Item dengan ID 1 tidak ditemukan"),
                    ]
                )
            )
        ]
    )]
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

    #[OA\Patch(
        path: "/api/items/{id}",
        summary: "Mengupdate sebagian data item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Laptop Pro"),
                    new OA\Property(property: "harga", type: "number", example: 20000000),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Item berhasil diupdate sebagian",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Item berhasil diupdate sebagian"),
                        new OA\Property(property: "data", type: "object", properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "nama", type: "string", example: "Laptop Pro"),
                            new OA\Property(property: "harga", type: "number", example: 20000000),
                        ])
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Item tidak ditemukan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Item dengan ID 1 tidak ditemukan"),
                    ]
                )
            )
        ]
    )]
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

    #[OA\Delete(
        path: "/api/items/{id}",
        summary: "Menghapus item",
        tags: ["Items"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Item berhasil dihapus",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Item berhasil dihapus"),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Item tidak ditemukan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Item dengan ID 1 tidak ditemukan"),
                    ]
                )
            )
        ]
    )]
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