<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Task;

class TasksController extends Controller
{

    public function index(Request $request){

        $searchPending = $request->query('searchPending');
        $searchComplete = $request->query('searchComplete');

        $pending = Task::where('user_id', Auth::id())->where('complete', false)->
            when($searchPending, function($query) use ($searchPending) {
                $query->where(function ($q) use ($searchPending){
                    $q->where('title', 'like', "%{$searchPending}%");
                });
            })

        ->get();      
    
        $complete = Task::where('user_id', Auth::id())->where('complete', true)->
            when($searchComplete, function($query) use ($searchComplete) {
                $query->where(function ($q) use ($searchComplete){
                    $q->where('title', 'like', "%{$searchComplete}%");
                });
            })

        ->orderByDesc('complete_in')
        ->get();    

        return view('teste', compact('complete', 'pending'));
    }

    public function store(Request $request){

        $task = new Task;

            $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'date' => 'date_format:Y-m-d|after_or_equal:today|before_or_equal:2999-12-31'],
            [
            'title.required' => 'O nome é obrigatorio',
            'date.after_or_equal' => 'A data não pode ser anterior a hoje.',
            'date.date_format' => 'Informe uma data válida',
             
        ]);

        $task->title = $request->title;
        $task->description = $request->description ?? '';
        $task->date = $request->date;
        $task->user_id = Auth::id();

        $task -> save();

        return redirect('/')->with('msg', 'Nova tarefa criada com sucesso!');
    }

    public function destroy(Task $task){

        $task->delete();

        abort_if($task->user_id !== Auth::id(), 403);

        return redirect('/');

    }

    public function complete(Task $task){
        $task->complete = true;

        abort_if($task->user_id !== Auth::id(), 403);

        $task->complete_in = today();

        $task->save();

        return redirect('/') ->with('msg', 'Tarefa concluida!');
    }

    public function update(Request $request, Task $task){

        abort_if($task->user_id !== Auth::id(), 403);

        $request->validateWithBag('edit',
        [
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'date' => 'date_format:Y-m-d|after_or_equal:today|before_or_equal:2999-12-31'],[
            
            'title.required' => 'O nome é obrigatorio',
            'date.after_or_equal' => 'A data não pode ser anterior a hoje.',
            'date.date_format' => 'Informe uma data válida',
            ]);   
        
        $task->title = $request->title;
        $task->description = $request->description ?? '';
        $task->date = $request->date;
        $task->save();

        return redirect('/')->with('msg', 'Tarefa Editada!');
    }
}
