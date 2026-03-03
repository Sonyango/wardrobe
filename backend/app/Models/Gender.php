<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Gender extends Model
{
    protected $fillable = ['name'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
