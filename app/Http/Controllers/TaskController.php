<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function markCompletion(Request $request, $id){
        $userId = $request->user()->id;
        Task::create(['user_id' => $userId, 'module_id' => $id]);
        return redirect()->back()->with('info', 'Отправлено на проверку');
    }

    public function updateStatus(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,completed,failed',
            'comment' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Получаем задание по его ID
        $task = Task::findOrFail($id);

        // Обновляем статус и комментарий
        $task->status = $request->input('status');
        $task->save();

        $taskId = $task->id;
        if(!empty($request->input('comment'))){
            $comment = $request->input('comment');
            $data = ['task_id' => $taskId, 'text' => $comment];
            Comment::create($data);
        }

        return redirect()->back()->with('success', 'Статус и комментарий успешно обновлены.');
    }
}
