<?php

namespace App\Domain;
use Illuminate\Database\Eloquent\Model;

class Account  {

    public int $id;
    public string $name;
    public int $amount;


    public function __construct(int $id, string $name, int $amount) {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
    }
}
