<?php

namespace App\Dto;
use App\Models\Category;

class CategoryResponseDto {
    public int $id;
    public string $name;
    public string $type;

    public function __construct(Category $category)
    {
        $this->id = $category->id;
        $this->name = $category->name;
        $this->type = $category->type;
    }
}