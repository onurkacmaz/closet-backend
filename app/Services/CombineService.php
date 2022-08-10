<?php

namespace App\Services;

use App\Models\Combine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CombineService
{
    public function getCombines(): Collection
    {
        return Combine::query()->withSum('combineItems.combineItemReaction', 'like')->get();
    }

    public function getCombine(int $combineId): Model|Combine|null
    {
        return Combine::query()->where('id', $combineId)->first();
    }

    public function delete(int $combineId): bool
    {
        return $this->getCombine($combineId)?->delete();
    }
}
