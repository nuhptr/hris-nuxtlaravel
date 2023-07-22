<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'company_id'
    ];

    // relationships
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // relationships
    public function responsibilities(): HasMany
    {
        return $this->hasMany(Responsibility::class);
    }

    // relationships
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
