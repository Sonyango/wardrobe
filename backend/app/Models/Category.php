<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Gender;
use App\Models\Item;

class Category extends Model
{
    protected $fillable = ['gender_id', 'name', 'type'];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
