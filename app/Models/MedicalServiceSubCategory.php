<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalServiceSubCategory extends Model
{
    use SoftDeletes;

    protected $table = 'medical_service_sub_categories';

    protected $fillable = [
        'category_id', 'subcategory_name', 'slug', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function category()
    {
        return $this->belongsTo(MedicalServiceCategory::class, 'category_id');
    }
}