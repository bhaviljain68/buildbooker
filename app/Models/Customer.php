<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'mobile',
    //     'address',
    //     'project_id'
    // ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    // Managing Soft Deletes
    protected static function booted()
    {
        // When a Project is soft-deleted
        static::deleting(function ($customer): void {
            if (! $customer->isForceDeleting()) {
                $customer->transactions()->delete();
            }
        });

        // When a Project is restored
        static::restoring(function ($customer): void {
            $customer->transactions()->withTrashed()->restore();
        });
    }
}
