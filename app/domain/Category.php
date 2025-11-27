<?php

namespace App\Domain;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    public const TYPE_INCOME = 'income';
    public const TYPE_EXPENSE = 'expense';

    public int $id;
    public string $name;
    public string $type;

    protected static function booted(){
        static::saving(function(Category $category) {
            if(empty($category->name)) {
                throw new \InvalidArgumentException('Назва не може бути порожньою');
            }
            if(!in_array($category->type, [self::TYPE_INCOME, self::TYPE_EXPENSE])) {
                throw new \InvalidArgumentException("Недійсний тип категорії: {$category->type}");
            }
        });
    }

    public function isIncomeCategory(): bool {
        return $this->type === self::TYPE_INCOME;
    }

    public function isExpenseCategory(): bool {
        return $this->type === self::TYPE_EXPENSE;
    }

    public function __construct(int $id, string $name, string $type){
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }
}