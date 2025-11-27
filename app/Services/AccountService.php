<?php

namespace App\Services;

use App\Dto\AccountDto;
use App\Dto\AccountRequestDto;
use App\Dto\AccountResponseDto;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Traits\ApiResponse;
use Exception;
class AccountService {

    public function __construct(protected AccountRepository $accountRepository)
    {
    }

    public function getAll(): array
    {
        return array_map(
            fn($t) => new AccountResponseDto($t),
            $this->accountRepository->all()->all()
        );
    }

    public function store(AccountRequestDto $dto) : AccountResponseDto {
        $account = $this->accountRepository->create($dto);
        return new AccountResponseDto($account);

    }

    public function deleteByID(int $id): bool {
        return $this->accountRepository->deleteById($id);

    }

    public function update(AccountDto $accountDto): AccountResponseDto {
        $account = $this->accountRepository->update($accountDto);
        return new AccountResponseDto($account);
    }
}
