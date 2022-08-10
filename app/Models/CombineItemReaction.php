<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CombineItemReaction extends Model
{
    use HasFactory;

    protected $table = 'combine_item_reactions';
    protected $guarded = [];

    public function closetItem(): HasOne {
        return $this->hasOne(CombineItem::class);
    }
}
