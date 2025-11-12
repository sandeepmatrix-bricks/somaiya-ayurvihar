<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KjshSubCategory extends Model
{
    use SoftDeletes;

    protected $table = 'kjsh_sub_categories';

    protected $fillable = [
        'category_id', 'subcategory_name', 'slug', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}