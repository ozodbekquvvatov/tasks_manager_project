<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all(); // Barcha vazifalar

        $totalTasks = $tasks->count(); // Jami vazifalar
        $completedTasks = $tasks->where('status', 'Completed')->count(); // Yakunlangan vazifalar
        $inProgressTasks = $tasks->where('status', 'In Progress')->count(); // Davom etayotgan vazifalar
    
        return view('dashboard', compact('totalTasks', 'completedTasks', 'inProgressTasks','tasks'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("auth.register");
    }
    public function login(Request $request)
    {
        return view("auth.login");
        
    }
    public function loginStore(UserLoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate(); 
            return redirect()->route('users.index'); 
        }

        return back()->withErrors([
            'email' => 'Login yoki parol noto‘g‘ri.'
        ])->withInput();
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    public function logout(Request $request)
    {
        Auth::logout(); // foydalanuvchini chiqish qilish

        $request->session()->invalidate(); // sessionni bekor qilish
        $request->session()->regenerateToken(); // CSRF tokenni yangilash
    
        return redirect()->route('login'); // foydalanuvchini login sahifasiga yo‘naltirish
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
