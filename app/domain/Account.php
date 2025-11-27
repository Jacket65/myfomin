<?php

namespace App\Domain;
use Illuminate\Database\Eloquent\Model;

class Account extends Model {
    // protected $fillable = ['id', 'name', 'amount'];
    public int $id;
    public string $name;
    public int $amount;

    public function withdraw(int $amount) {
        if($this->amount < $amount) {
            throw new \Exception("Недостатньо коштів на рахунку {$this->id}");
        }
        $this->amount -= $amount;
        $this->save();
    }

    public function deposit(int $amount) {
        if($amount <= 0) {
            throw new \InvalidArgumentException("Сума депозиту має бути додатньою");
        }
        $this->amount += $amount;
        $this->save();
    }

    public function __construct(int $id, string $name, int $amount) {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
    }
}