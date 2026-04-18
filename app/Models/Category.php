<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = ['name', 'description', 'color'];

    // A category can have many events
    public function events() {
        return $this->hasMany(Event::class);
    }
}