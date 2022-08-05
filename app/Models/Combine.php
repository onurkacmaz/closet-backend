<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Combine extends Model
{
    use HasFactory;

    protected $table = 'combines';
    protected $guarded = [];
    protected $with = ['combineItems', 'user'];

    public function user(): HasOne {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function combineItems(): HasMany
    {
        return $this->hasMany(CombineItem::class, 'combine_id', 'id');
    }
}
