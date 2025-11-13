<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'doctors';

    protected $fillable = [ 'salutation', 'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth', 'profile_image', 'short_video', 
                            'medical_service_sub_category_id', 'registration_number', 'registration_council', 'registration_year', 'experience_years', 
                            'degrees', 'languages', 'consultation_fee', 'available_days', 'consultation_timings', 'phone', 'whatsapp', 'email', 'address_line_1', 
                            'address_line_2', 'city', 'state', 'pincode', 'is_active', 'is_verified', 'is_featured', 'rating', 'total_reviews', 'created_by', 'updated_by', 'deleted_by', ];

    protected $casts = [
        'available_days' => 'array',
        'consultation_timings' => 'array',
        'consultation_fee' => 'decimal:2',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'date_of_birth' => 'date',
        'registration_year' => 'integer',
        'experience_years' => 'integer',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'full_address',
        'morning_time',
        'evening_time',
        'is_available_today',
    ];

    // ==================== RELATIONSHIPS ====================

    public function subcategory()
    {
        return $this->belongsTo(MedicalServiceSubCategory::class, 'medical_service_sub_category_id');
    }

    public function category()
    {
        return $this->hasOneThrough(
            MedicalServiceCategory::class,
            MedicalServiceSubCategory::class,
            'id',
            'id',
            'medical_service_sub_category_id',
            'category_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function unavailableDates()
    {
        return $this->hasMany(DoctorUnavailableDate::class);
    }

    // ==================== ACCESSORS ====================

    public function getFullNameAttribute(): string
    {
        return trim("{$this->salutation} {$this->first_name} " . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }

    public function getFullAddressAttribute(): string
    {
        return trim("{$this->address_line_1}" . ($this->address_line_2 ? ', ' . $this->address_line_2 : '') . ", {$this->city}, {$this->state} - {$this->pincode}");
    }

    public function getMorningTimeAttribute(): ?string
    {
        return $this->consultation_timings['morning'] ?? null;
    }

    public function getEveningTimeAttribute(): ?string
    {
        return $this->consultation_timings['evening'] ?? null;
    }

    public function getIsAvailableTodayAttribute(): bool
    {
        $today = now()->format('l');
        $dateToday = now()->format('Y-m-d');

        if (!in_array($today, $this->available_days ?? [])) {
            return false;
        }

        return !$this->unavailableDates()
            ->where('unavailable_date', $dateToday)
            ->exists();
    }

    public function getProfileImageUrlAttribute(): string
    {
        return $this->profile_image 
            ? asset('storage/' . $this->profile_image) 
            : asset('assets/images/doctor-avatar.jpg');
    }

    public function getShortVideoUrlAttribute(): ?string
    {
        return $this->short_video ? asset('storage/' . $this->short_video) : null;
    }

    // ==================== MUTATORS ====================

    protected function consultationFee(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeAvailableToday($query)
    {
        $today = now()->format('l');
        $dateToday = now()->format('Y-m-d');

        return $query->whereJsonContains('available_days', $today)
            ->whereDoesntHave('unavailableDates', fn($q) => $q->where('unavailable_date', $dateToday));
    }

    public function scopeBySpeciality($query, $subcategoryId)
    {
        return $query->where('medical_service_sub_category_id', $subcategoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->whereRaw(
            "MATCH(first_name, last_name, degrees, languages) AGAINST(? IN NATURAL LANGUAGE MODE)",
            [$term]
        );
    }

    // ==================== CUSTOM METHODS ====================

    public function getAvailableDaysString(): string
    {
        return $this->available_days 
            ? implode(', ', $this->available_days) 
            : 'Not set';
    }

    public function getTimingsString(): string
    {
        $timings = [];
        if ($this->morning_time) $timings[] = $this->morning_time;
        if ($this->evening_time) $timings[] = $this->evening_time;
        return $timings ? implode(' | ', $timings) : 'Not set';
    }

    public function isOnLeave(\DateTimeInterface $date): bool
    {
        return $this->unavailableDates()
            ->where('unavailable_date', $date->format('Y-m-d'))
            ->exists();
    }
}