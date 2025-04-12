<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use Log;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
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
    
        return view('task_crud.index', compact('totalTasks', 'completedTasks', 'inProgressTasks', 'tasks'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        return view('task_crud.create'); // Vazifa yaratish formasi
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
{
    // Faylni tekshirish
    $filePath = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        // Faylni "images" papkasiga saqlash
        $filePath = $file->storeAs('images', $fileName, 'public');
    }

    // Taskni yaratish va ma'lumotlarni saqlash
    Task::create([
        'task_name' => $request->task_name,
        'priority' => $request->priority,
        'due_date' => $request->due_date,
        'description' => $request->description ?? null,
        'image' => $filePath,  // Fayl yo'lini saqlash
    ]);

    // Muvaffaqiyatli qo'shildi deb xabar berish
    return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $tasks = Task::all(); // Barcha vazifalar
    
        $totalTasks = $tasks->count(); // Jami vazifalar
        $completedTasks = $tasks->where('status', 'Completed')->count(); // Yakunlangan vazifalar
        $inProgressTasks = $tasks->where('status', 'in-progress')->count(); // Davom etayotgan vazifalar
        
        return view('task_crud.index', compact('totalTasks', 'completedTasks', 'inProgressTasks', 'tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id); // ID bo'yicha taskni topish
        return view('task_crud.edit', compact('task')); // Taskni tahrirlash formasi
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, string $id)
{
    $task = Task::findOrFail($id); // Taskni ID bo'yicha topish

    // Yangi rasm yuklanayotganini tekshirish
    if ($request->hasFile('image')) {
        // Eski rasmni o'chirish
        if ($task->image) {
            $oldImagePath = public_path('storage/' . $task->image); // Eski rasmning to'g'ri yo'li
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);  // Eski rasmni o'chirish
            }
        }

        // Yangi rasmni yuklash
        $file = $request->file('image');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('images', $fileName, 'public');  // "public" diskiga rasmni saqlash
        
        // Taskni yangi rasm yo'li bilan yangilash
        $task->image = $filePath;
    }

    // Taskning boshqa ma'lumotlarini yangilash
    $task->update([
        'task_name' => $request->task_name,
        'priority' => $request->priority,
        'due_date' => $request->due_date,
        'description' => $request->description ?? null,
    ]);

    // Muvaffaqiyatli yangilandi deb xabar berish
    return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
}


    

    
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete(); // Taskni o'chirish

        // Muvaffaqiyatli o'chirildi deb xabar berish
        return redirect()->route('tasks.index');
    }
}
