<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Closet;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ClosetService
{
    public function getClosets(User|Authenticatable $user, string|null $searchKeywords): Collection
    {
        return $user
            ->closets()
            ->when(!is_null($searchKeywords), function ($query) use ($searchKeywords) {
                return $query->where('name', 'like', '%'.$searchKeywords.'%');
            })
            ->with('items.closet', 'items.photos', 'items.relatedLinks')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function delete(int $closetId): bool
    {
        return $this->getCloset($closetId)?->delete();
    }

    public function getCloset(int $closetId): Model|Closet|null
    {
        return Closet::query()->where('id', $closetId)->with('items.photos', 'items.relatedLinks')->first();
    }

    /**
     * @throws ApiException
     */
    public function update(int $closetId, string $name, string $description): Model|Closet
    {
        $closet = $this->getCloset($closetId);
        if (is_null($closet)) {
            throw new ApiException('CLOSET_NOT_FOUND', 422);
        }

        $closet->update([
            'name' => $name,
            'description' => $description,
        ]);
        return $closet;
    }

    public function create(string $name, string $description): Model|Closet
    {
        return Closet::query()->create([
            'user_id' => auth()->id(),
            'name' => $name,
            'description' => $description,
        ]);
    }
}
