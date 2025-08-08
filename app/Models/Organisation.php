<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    // protected $with = ['customers'];
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($value) => asset($value),
        );
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Project::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(
            Transaction::class, // Final model
            Project::class,     // Intermediate model
            'organisation_id',  // Foreign key on Project table
            'project_id',       // Foreign key on Transaction table
            'id',               // Local key on Organisation table
            'id'                // Local key on Project table
        )->withTrashed();
    }

    // Managing Soft Deletes
    protected static function booted()
    {
        // When an Organisation is soft-deleted
        static::deleting(function ($organisation): void {
            if (! $organisation->isForceDeleting()) {
                $organisation->users()->delete();
                $organisation->projects()->delete();
                $organisation->customers()->delete();
            }
        });

        // When an Organisation is restored
        static::restoring(function ($organisation): void {
            $organisation->users()->withTrashed()->restore();
            $organisation->projects()->withTrashed()->restore();
            $organisation->customers()->withTrashed()->restore();
        });
    }
}
