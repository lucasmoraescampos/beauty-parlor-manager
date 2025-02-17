<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'job_title_id',
        'name',
        'registration_number',
        'profile',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uid)) {
                $model->uid = Str::uuid()->toString();
            }
        });
    }

    protected function profile(): Attribute
    {
        return Attribute::make(
            get: function (?string $value) {
                if (empty($value)) {
                    $nameArr = explode(' ', $this->name);
                    $name = strtoupper($nameArr[0][0]);
                    $name .= count($nameArr) > 1 ? ' ' . strtoupper($nameArr[count($nameArr) - 1][0]) : '';
                    return "https://ui-avatars.com/api/?name={$name}&color=FFFFFF&background=09090b";
                }
                return asset('storage/' . $value);
            },
        );
    }

    protected function link(): Attribute
    {
        return Attribute::make(
            get: fn () => url("/api/employee/{$this->uid}"),
        );
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class);
    }
}
