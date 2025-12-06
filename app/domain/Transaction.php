<?php

namespace App\Domain;
use Illuminate\Database\Eloquent\Model;

class Transaction {
    public int $id;
    public int $account_id;
    public string $type;
    public int $amount;
    public string $created_at;
    public string $description;
    public int $category_id;


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
