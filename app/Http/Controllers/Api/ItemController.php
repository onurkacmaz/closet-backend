<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Resources\GetItemResource;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;

class ItemController
{

    public function __construct(public ItemService $itemService){}

    public function view(int $id): GetItemResource
    {
        return new GetItemResource($this->itemService->getItemById($id));
    }

    public function store(ItemStoreRequest $request): GetItemResource
    {
        return new GetItemResource(
            $this->itemService->create(
                $request->get('closetId'),
                $request->get('name'),
                $request->get('note'),
                $request->get('photos'),
                $request->get('links', []),
            )
        );
    }

    /**
     * @throws ApiException
     */
    public function update(int $itemId, ItemUpdateRequest $request): GetItemResource
    {
        return new GetItemResource(
            $this->itemService->update(
                $itemId,
                $request->get('name'),
                $request->get('note'),
                $request->get('photos'),
                $request->get('links', []),
            )
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->itemService->delete($id);
        return response()->json(null, 204);
    }
}
