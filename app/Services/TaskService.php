<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService
{
    public function getTasksForUser(int $userId):Collection
    {
        return Task::where('user_id',$userId)->get();
    }

    public function getTasksByModule(int $moduleId, int $userId): Collection
    {
        return Task::where('module_id', $moduleId)
            ->where('user_id', $userId)
            ->get();
    }


    public function calculateCompletionPercentage(Collection $tasks): float
    {
        $completedTasks = $tasks->where('status', 'completed')->count();
        $totalTasks = $tasks->count();

        return $totalTasks > 0 ? ($completedTasks/ $totalTasks) * 100 : 0;
    }

    public function translateStatus(string $status): string
    {
        $translations = [
            'pending'   => 'В ожидании проверки',
            'completed' => 'Выполнено',
            'failed'    => 'Ошибка выполнения',
        ];

        return $translations[$status] ?? $status;
    }
}
