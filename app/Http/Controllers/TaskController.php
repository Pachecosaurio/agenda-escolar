<?php
/**
 * Controlador de Tareas
 *
 * Responsabilidades:
 * - CRUD de tareas del usuario autenticado.
 * - Búsqueda por título y filtrado por fecha de vencimiento.
 * - Soporta adjuntos almacenados en storage/public/attachments.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
    $query = Task::where('user_id', Auth::id());
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->due_date);
        }
        $tasks = $query->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'due_date.date' => 'La fecha límite debe ser una fecha válida.',
            'attachment.file' => 'El archivo adjunto debe ser un archivo válido.',
            'attachment.max' => 'El archivo adjunto no puede superar los 10MB.',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'due_date' => $request->due_date,
            'attachment' => $attachmentPath,
            'completed' => $request->has('completed'),
        ]);

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240',
        ], [
            'title.required' => 'El título es obligatorio.',
            'title.max' => 'El título no puede tener más de 255 caracteres.',
            'due_date.date' => 'La fecha límite debe ser una fecha válida.',
            'attachment.file' => 'El archivo adjunto debe ser un archivo válido.',
            'attachment.max' => 'El archivo adjunto no puede superar los 10MB.',
        ]);
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'completed' => $request->has('completed'),
        ];
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }
        $task->update($data);
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index');
    }
}
