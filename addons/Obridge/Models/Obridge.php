<?php

namespace Obelaw\Obridge\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Obelaw\Twist\Base\BaseModel;

class Obridge extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'secret',
        'description',
        'is_active',
        'last_used_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'secret',
    ];

    /**
     * Generate a secure random secret.
     */
    public static function generateSecret(): string
    {
        return Str::random(64);
    }

    /**
     * Update the last used timestamp.
     */
    public function updateLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if the obridge is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Scope to only include active obridges.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Hash the secret when storing (if configured).
     */
    public function setSecretAttribute($value): void
    {
        if (config('obridge.security.hash_secrets', false)) {
            $this->attributes['secret'] = Hash::make($value);
        } else {
            $this->attributes['secret'] = $value;
        }
    }

    /**
     * Check if the provided secret matches.
     */
    public function checkSecret(string $secret): bool
    {
        if (config('obridge.security.hash_secrets', false)) {
            return Hash::check($secret, $this->secret);
        }

        return $this->secret === $secret;
    }
}
