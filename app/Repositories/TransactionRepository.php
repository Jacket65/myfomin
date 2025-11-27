<?php

namespace App\Repositories;

use App\Dto\TransactionDto;
use App\Dto\TransactionRequestDto;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository
{
    public function __construct(protected Transaction $transaction) {}

    public function get() :Collection {
        return $this->transaction->with('category', 'account')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getById(int $id) :Transaction {
        return $this->transaction->where('id', $id)->first();
    }

    public function create(array $dto) : Transaction {
        return $this->transaction->create([
            'account_id' => $dto['account_id'],
            'type' => $dto['type'],
            'amount' => $dto['amount'],
            'description' => $dto['description'],
            'category_id' => $dto['category_id'],
        ]);
    }

    public function update(int $id, array $data) : Transaction {
         $transaction = $this->transaction->where('id', $id)->first();
        $transaction->account_id = $data['account_id'];
        $transaction->type = $data['type'];
        $transaction->amount = $data['amount'];
        $transaction->description = $data['description'] ?? null;
        $transaction->category_id = $data['category_id'];
        if ($data['created_at'] !== null) {
            $transaction->created_at = $data['created_at'];
        }
        $transaction->save();
        return $transaction;
    }



    public function deleteById($id) : bool {
        return $this->transaction->where('id', $id)->delete() > 0;
    }
}
