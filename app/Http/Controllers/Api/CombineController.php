<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CombineResource;
use App\Services\CombineService;
use Illuminate\Http\JsonResponse;

class CombineController
{

    public function __construct(public CombineService $combineService){}

    public function index(): JsonResponse
    {
        return CombineResource::collection($this->combineService->getCombines())->response();
    }

    public function view(int $id): CombineResource
    {
        return new CombineResource($this->combineService->getCombine($id));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->combineService->delete($id);
        return response()->json(null, 204);
    }
}
