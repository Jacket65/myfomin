<?php

namespace App\Services;
use App\Dto\AccountDto;
use App\Dto\TransactionDto;
use App\Dto\TransactionRequestDto;
use App\Dto\TransactionResponseDto;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Transaction;

class TransactionService
{
    protected TransactionRepository $transactionRepository;
    protected AccountRepository $accountRepository;
    public function __construct(TransactionRepository $transactionRepository, AccountRepository $accountRepository) {
        $this->transactionRepository = $transactionRepository;
        $this->accountRepository = $accountRepository;

    }

    public function get() : array{
        $transactions = $this->transactionRepository->get();

        return $transactions->map(function($transaction){
            return new TransactionResponseDto($transaction);
        })->all();
    }

    public function store(TransactionRequestDto $dto) : TransactionResponseDto {
        return DB::transaction(function () use ($dto) {
            $dataArray = [
            'account_id' => $dto->account_id,
            'category_id' => $dto->category_id, 
            'type' => $dto->type,
            'amount' => $dto->amount,
            'description' => $dto->description ?? "", 
            'created_at' => $dto->created_at ?? null,
        ];
           $transaction =  $this->transactionRepository->create($dataArray);
           $this->accountRepository->changeAmount($dto->account_id, $dto->amount, $dto->type);

           return new TransactionResponseDto($transaction->load('category', 'account'));

        });
    }

    // the transaction type will not change in the future
    public function update(int $id, TransactionDto $dto) : TransactionResponseDto {

        return DB::transaction(function () use ($id, $dto) {
            $transaction = $this->transactionRepository->getById(id: $id);

            $change = $dto->amount - $transaction->amount;

            $dataArray = [
            'account_id' => $dto->account_id,
            'category_id' => $dto->category_id, 
            'type' => $dto->type,
            'amount' => $dto->amount,
            'description' => $dto->description ?? "", 
            'created_at' => $dto->created_at ?? null,
            ];

            $updatedTransaction = $this->transactionRepository->update($id, $dataArray);
            $this->accountRepository->changeAmount($dto->account_id, $change, $dto->type);
            return new TransactionResponseDto($updatedTransaction->load('category', 'account'));

        });



    }

    public function deleteByID(int $id) : bool {
        return $this->transactionRepository->deleteByID($id);
    }

}
