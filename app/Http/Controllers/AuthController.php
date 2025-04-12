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
    public function index(Request $request)
    {
        $tasks = Task::all(); // Barcha vazifalar
    
        $totalTasks = $tasks->count(); // Jami vazifalar
        $completedTasks = $tasks->where('status', 'Completed')->count(); // Yakunlangan vazifalar
        $inProgressTasks = $tasks->where('status', 'in-progress')->count(); // Davom etayotgan vazifalar
        $query = Task::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
    
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
    
        $tasks = $query->latest()->paginate(10); // sahifalash
    
        return view('dashboard', compact('totalTasks', 'completedTasks', 'inProgressTasks', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("auth.register");
    }

    /**
     * Show the login form.
     */
    public function login(Request $request)
    {
        return view("auth.login");
    }

    /**
     * Handle user login.
     */
    public function loginStore(UserLoginRequest $request)
    {
        // Foydalanuvchi tizimga kirishga harakat qilish
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate(); // sessionni yangilash
            return redirect()->route('dashboard'); // Foydalanuvchini dashboardga yo‘naltirish
        }

        // Agar login yoki parol noto‘g‘ri bo‘lsa
        return back()->withErrors([
            'email' => 'Login yoki parol noto‘g‘ri.'
        ])->withInput();
    }

    /**
     * Store a newly created user in storage and log them in.
     */
    public function store(UserStoreRequest $request)
    {
        // Foydalanuvchini ro'yxatdan o'tkazish
        $user = User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Yangi foydalanuvchini tizimga kiriting
        Auth::login($user);

        // Foydalanuvchi tizimga kiritilganidan so'ng, dashboardga yo‘naltirish
        return redirect()->route('dashboard');
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

    /**
     * Logout the user and redirect to login page.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Foydalanuvchini tizimdan chiqish

        $request->session()->invalidate(); // sessionni bekor qilish
        $request->session()->regenerateToken(); // CSRF tokenni yangilash

        return redirect()->route('login'); // Foydalanuvchini login sahifasiga yo‘naltirish
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
