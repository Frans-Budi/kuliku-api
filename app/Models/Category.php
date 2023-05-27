<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ["name"];

    public function projects(): MorphToMany
    {
        return $this->morphedByMany(Project::class, "categoryable");
    }
}
