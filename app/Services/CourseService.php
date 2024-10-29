<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Request as RequestModel;
use App\Services\TaskService;
use Illuminate\Support\Str;

class CourseService
{
    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }


    public function createCourse($request)
    {
        $data = $this->validateCreateCourse($request);
        $data['slug'] = Str::slug($request->input('title'));
        return $data;
    }

    private function validateCreateCourse($request)
    {
        return $request->validate([
            'title' => 'required|max:255|unique:courses,title',
            'description' => 'required|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:10048',
        ],
            [
                'title.required' => 'Поле "Название" обязательно для заполнения.',
                'title.max' => 'Поле "Название" не должно превышать 255 символов.',
                'title.unique' => 'Курс с таким названием уже существует.',

                'description.required' => 'Поле "Описание" обязательно для заполнения.',
                'description.max' => 'Поле "Описание" не должно превышать 255 символов.',

                'logo.required' => 'Поле "Логотип" обязательно для заполнения.',
                'logo.image' => 'Файл, загружаемый в поле "Логотип", должен быть изображением.',
                'logo.mimes' => 'Поле "Логотип" должно быть файлом формата: jpeg, png, jpg, svg.',
                'logo.max' => 'Размер файла "Логотип" не должен превышать 10 МБ.',
            ]);
    }

    public function getCoursesForUser(int $userId)
    {
        return Course::whereHas('requests', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('status', 'accepted');
        })->get();
    }

    public function calculateCourseProgress(Course $course, int $userId): int
    {
        $modules = $course->modules;
        $totalTasksCount = 0;
        $modulesCount = $modules->count();
        $completedTasksCount = 0;

        foreach ($modules as $module) {
            $tasks = $this->taskService->getTasksByModule($module->id, $userId);
            $completedTasksCount += $tasks->where('status', 'completed')->count();
            $totalTasksCount += $tasks->count();
        }

        return $totalTasksCount > 0 ? ($completedTasksCount / $modulesCount) * 100 : 0;
    }

}
