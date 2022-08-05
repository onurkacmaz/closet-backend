<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Combine;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CombineItem extends Model
{
    use HasFactory;

    protected $table = "combine_items";
    protected $guarded = [];
    protected $with = ['item.photos'];

    public function combine(): HasOne
    {
        return $this->hasOne(Combine::class, 'id', 'combine_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
