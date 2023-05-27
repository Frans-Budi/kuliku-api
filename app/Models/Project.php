<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, "categoryable");
    }

    public function project_images(): MorphMany
    {
        return $this->morphMany(ProjectImage::class, "project_imageable");
    }

    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, "addressable");
    }

    public function users(): MorphToMany
    {
        return $this->morphToMany(User::class, "projectable");
    }

    public function testimonials(): MorphMany
    {
        return $this->morphMany(Comment::class, "testimonyable");
    }
}
