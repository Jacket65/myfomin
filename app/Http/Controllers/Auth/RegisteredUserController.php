<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $defaultCategories = [
            ['name' => 'Спорт',            'type' => 'spend'],
            ['name' => 'Догляд за собою',  'type' => 'spend'],
            ['name' => 'Супермаркети',     'type' => 'spend'],

            ['name' => 'Зарплата',         'type' => 'deposit'],
            ['name' => 'Подарунки',        'type' => 'deposit'],
            ['name' => 'Стипендія',        'type' => 'deposit'],
        ];

        $categories = array_map(function ($item) use ($user) {
            return [
                'name'    => $item['name'],
                'type'    => $item['type'],
                'user_id' => $user->id,
            ];
        }, $defaultCategories);

        Category::insert($categories);


        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
