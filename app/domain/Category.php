<?php

namespace App\Domain;
use Illuminate\Database\Eloquent\Model;

class Category {
    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';

    public int $id;
    public string $name;
    public string $type;


    public function __construct(int $id, string $name, string $type){
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }
}
