<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['sold_units'];

    // Accessor for 'logo' to return asset URL
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset($value),
        );
    }

    protected function soldUnits(): Attribute
    {
        return Attribute::make(
            get: function () {
                return Unit::where('project_id', $this->id)->whereNotNull('customer_id')->count();
            },
        );
    }

    protected $with = ['units'];

    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Unit::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    // Managing Soft Deletes
    protected static function booted()
    {
        // When a Project is soft-deleted
        static::deleting(function ($project): void {
            if (! $project->isForceDeleting()) {
                $project->customers()->delete();
                $project->transactions()->delete();
                $project->units()->delete();
            }
        });

        // When a Project is restored
        static::restoring(function ($project): void {
            $project->customers()->withTrashed()->restore();
            $project->transactions()->withTrashed()->restore();
            $project->units()->withTrashed()->restore();
        });
    }
}
