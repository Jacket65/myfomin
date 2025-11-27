<?php

namespace App\Dto;


use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;

class TransactionResponseDto {

    public int $id;
    public int $account_id;
    public string $type;
    public int $amount;
    public string $created_at;
    public ?int $category_id;
    public string $description;

    public Category $category;
    public Account $account;

    public function __construct(Transaction $transaction) {
        $this->id = $transaction->id;
        $this->account_id = $transaction->account_id;
        $this->category_id = $transaction->category_id;
        $this->type = $transaction->type;
        $this->amount = $transaction->amount;
        $this->description = $transaction->description ?? "";
        $this->created_at = $transaction->created_at instanceof \Carbon\Carbon
            ? $transaction->created_at->toDateTimeString()
            : $transaction->created_at;


        $this->category = $transaction->category;
        $this->account = $transaction->account;
    }
}
