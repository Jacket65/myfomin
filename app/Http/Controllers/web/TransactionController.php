<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Отримуємо всі транзакції разом з їхніми категоріями
        $transactions = Transaction::with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('transactions.index', compact('transactions'));
    }


    public function create()
    {
        $type = request('radio');
        $accountID = request('account_id');
        $amount = request('amount');

        Transaction::create([
            'type' => $type,
            'amount' => $amount,
            'account_id' => $accountID,
            'category_id' => request('category_id'),
            'created_at' => now(),
            'description' => request('description') ?? ''
        ]);

        if ($type === 'deposit') {
            Account::where('id', $accountID)->increment('amount', $amount);
        } else {
            Account::where('id', $accountID)->decrement('amount', $amount);
        }
        return redirect(route("accounts.index"));
    }



    public function destroy($id)
    {
        $transaction = Transaction::where('id', $id)
            ->whereHas('account', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        DB::transaction(function () use ($transaction) {

            $account = Account::findOrFail($transaction->account_id);

            if ($transaction->type === 'deposit') {
                $account->amount -= $transaction->amount;
            } else {
                $account->amount += $transaction->amount;
            }

            $account->save();
            $transaction->delete();
        });

        return redirect()->back()->with('success', 'Транзакцію видалено.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type'   => 'required|in:deposit,spend',
            'category_id' => 'required|integer',
        ]);

        $transaction = Transaction::where('id', $id)
            ->whereHas('account', fn($q) => $q->where('user_id', auth()->id()))
            ->firstOrFail();

        DB::transaction(function () use ($request, $transaction) {

            $account = Account::findOrFail($transaction->account_id);

            // 2. СКАСОВУЄМО стару транзакцію
            if ($transaction->type === 'deposit') {
                $account->amount -= $transaction->amount;
            } else { // spend
                $account->amount += $transaction->amount;
            }

            $transaction->update([
                'amount'      => $request->amount,
                'type'        => $request->type,
                'category_id' => $request->category_id,
                'description' => $request->description,
            ]);

            if ($transaction->type === 'deposit') {
                $account->amount += $transaction->amount;
            } else {
                $account->amount -= $transaction->amount;
            }

            $account->save();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Транзакцію успішно оновлено.');
    }
}
