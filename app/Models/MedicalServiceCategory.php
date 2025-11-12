<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalServiceCategory extends Model
{
    use SoftDeletes;

    protected $table = 'medical_service_categories';

    protected $fillable = [
        'category_name', 'slug', 'created_by', 'updated_by', 'deleted_by'
    ];

    public function subcategories()
    {
        return $this->hasMany(MedicalServiceSubCategory::class, 'category_id');
    }
}