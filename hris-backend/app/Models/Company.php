<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo'
    ];

    // relationships
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // relationships
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    // relationships
    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }
}
