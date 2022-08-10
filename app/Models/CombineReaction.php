<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CombineReaction extends Model
{
    use HasFactory;

    protected $table = 'combine_reactions';
    protected $guarded = [];

    public function combine(): HasOne {
        return $this->hasOne(Combine::class);
    }
}
