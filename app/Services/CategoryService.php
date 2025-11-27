<?php

namespace App\Services;
use App\Dto\CategoryResponseDto;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    public function getCategoriesByType(string $type): array {

        $categories = $this->categoryRepository->getByType($type);
        
        return $categories->map(function ($category) {
            return new CategoryResponseDto($category);
        })->all();
    }

}
