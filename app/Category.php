<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Categorizable models.
     *
     * @var array
     */
    public const Categorizables = [
        Product::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug'
    ];

    /**
     * Get category sub categories.
     *
     * @return HasMany
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}
