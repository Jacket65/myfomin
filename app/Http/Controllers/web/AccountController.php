<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AccountController extends Controller
{
    public function index() {
        $user = auth()->user();
        $accounts = $user->accounts()->get();

        return view('accounts.index', compact('accounts'));

    }

    public function store(): Redirector|RedirectResponse
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:20'],
            'amount' => ['required', 'numeric', 'max:100000000'],
        ]);
        $user = auth()->user();
        $user->accounts()->create([
            'name' => $validated['name'],
            'amount' => $validated['amount'],
        ]);

        return redirect(route("accounts.index"));
    }
    //
    public function delete(Account $account): RedirectResponse
    {

        dump($account);
        $this->authorize('delete', $account);
        $account->deleteOrFail();
        return redirect(route("accounts.index"));


//        $deletedCount =  Account::destroy($id);
//        if ($deletedCount > 0) {
//            return redirect(route('accounts.index'));
//        } else {
//            return redirect("/nigar");
//        }
    }

    public function update() {
        $id = request('id');
        Account::where('id', $id)->update([
            'name' => request('name'),
            'amount' => request('amount'),
        ]);

        return redirect(route('accounts.index'));
    }
}
