<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Requests\ClosetStoreRequest;
use App\Http\Requests\ClosetUpdateRequest;
use App\Http\Resources\ClosetResource;
use App\Http\Resources\GetClosetsResource;
use App\Services\ClosetService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ClosetController
{

    public function __construct(public ClosetService $closetService){}

    public function index(Request $request): GetClosetsResource
    {
        return new GetClosetsResource(
            $this->closetService->getClosets(
                Auth::user(),
                $request->get('searchKeywords')
            )
        );
    }

    /**
     * @throws ApiException
     */
    public function delete(int $closetId): Response
    {
        $response = $this->closetService->delete($closetId);
        if (!$response) {
            throw new ApiException('CLOSET_NOT_DELETED', 422);
        }
        return response()->noContent();
    }

    public function update(int $closetId, ClosetUpdateRequest $request): ClosetResource
    {
        return new ClosetResource(
            $this->closetService->update($closetId, $request->get('name'), $request->get('description'))
        );
    }

    public function view(int $closetId): ClosetResource
    {
        return new ClosetResource($this->closetService->getCloset($closetId));
    }

    public function store(ClosetStoreRequest $request): ClosetResource
    {
        return new ClosetResource(
            $this->closetService->create(
                $request->get('name'),
                $request->get('description')
            )
        );
    }

}
