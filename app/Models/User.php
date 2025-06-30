<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'organisation_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(organisation::class);
    }

    protected static function booted()
    {
        // When a User is soft-deleted
        static::deleting(function ($user): void {

            if (! $user->isForceDeleting()) {
                if (User::where('organisation_id', $user->organisation_id)->count() === 0) {
                    $user->organisation()->delete();
                }
            }
        });

        // When a User is restored
        static::restoring(function ($user): void {
            $user->organisation()->withTrashed()->restore();
        });
    }
}
