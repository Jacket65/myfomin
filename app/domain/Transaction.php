<?php

namespace App\Domain;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    public int $id;
    public int $account_id;
    public string $type;
    public int $amount;
    public string $created_at;
    public string $description;
    public int $category_id;

    protected static function booted(){
        static::saving(function (Transaction $transaction){
            if($transaction->amount <= 0){
                throw new \Exception("Сума транзакції повинна бути додатньою");
            }
        });
    }

    public function isIncome() : bool{
        return $this->type === 'income';
    }

    public function getOppositeAmount() : int{
        return $this->isIncome() ? $this->amount : -$this->amount;
    }

    public function __construct(int $id, int $account_id, string $type, int $amount, string $created_at, string $description, int $category_id){
        $this->id = $id;
        $this->account_id = $account_id;
        $this->type = $type;
        $this->amount = $amount;
        $this->created_at = $created_at;
        $this->description = $description;
        $this->category_id = $category_id;
    }
}