<?php

namespace App\Dto;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Electronics"),
 *     @OA\Property(property="type", type="string", example="product")
 * )
 */

class CategoryDto
{
    public int $id;
    public string $name;
    public string $type;

    public function __construct($id, $name, $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

}
