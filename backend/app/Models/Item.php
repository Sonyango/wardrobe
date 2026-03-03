<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Gender;
use App\Models\User;

class Item extends Model
{
    protected $fillable = ['user_id', 'category_id', 'gender_id', 'name', 'description', 'image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }
}
