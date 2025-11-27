<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{

    public function __construct(protected Category $category)
    {
    }

    public function getByType(string $type) : Collection {
        return $this->category->where('type', $type)->get();
    }

}
