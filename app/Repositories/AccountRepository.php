<?php

namespace App\Repositories;
use App\Dto\AccountDto;
use App\Dto\AccountRequestDto;
use App\Dto\AccountResponseDto;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Account;

class AccountRepository {


    public function __construct(protected Account $account)
    {


    }
    public function all(): Collection
    {
        return $this->account->all();
    }

    public function create(AccountRequestDto $data): Account {
        $user_id = 1;
        return $this->account->create([
            'user_id' => $user_id,
            'name' => $data->name,
            'amount' => $data->amount,
        ]);
    }

    public function deleteByID(int $id):bool {
        return $this->account->where('id', $id)->delete() > 0;
    }

    public function update(AccountDto $accountDto): Account {
        $account  = $this->account->where('id', $accountDto->id)->first();
        $account->name = $accountDto->name;
        $account->amount = $accountDto->amount;
        $account->save();
        return $account;
    }

    public function changeAmount(int $accountID, int $accountAmount, string $type): Account {
        $account = $this->account->where('id', $accountID)->first();
        if ($type === 'deposit') {
            $account->amount = $account->amount + $accountAmount;
        } else {
            $account->amount = $account->amount - $accountAmount;
        }
        $account->save();
        return $account;
    }
}
