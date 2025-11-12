<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Category extends Model
{



    use SoftDeletes;

    protected $fillable = [
        'category_name',
        'slug',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    public function subcategories()
    {
        return $this->hasMany(KjshSubCategory::class, 'category_id');
    }
}




