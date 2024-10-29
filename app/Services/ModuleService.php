<?php

namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ModuleService
{
    public function getModuleById(int $id)
    {
        return Module::findOrFail($id);
    }

    public function getModulesForCourse(int $courseId)
    {
        return Module::where('course_id', $courseId)->get();
    }

    public function createModule($request)
    {
        $data = $this->validateModule($request);
        $data['slug'] = Str::slug($request->input('title'));
        return $data;
    }

    public function updateModule($request, int $id)
    {
        $data = $this->validateModule($request, $id);
        $data['slug'] = Str::slug($request->input('title'));
        return $data;
    }

    private function validateModule($request, $ignoreId = null)
    {
        return $request->validate([
            'title' => [
                'required',
                'max:255',
                Rule::unique('modules')->ignore($ignoreId), // Уникальность, игнорируя текущий модуль
            ],
            'comment' => 'nullable|max:255',
            'theory' => 'required',
            'task' => 'required',
            'stat' => 'required|in:theory,practice',
            'status' => 'required|in:necessarily,not necessary',
            'course_id' => 'required|exists:courses,id',
        ],
            [
                'title.required' => 'Поле "Название" обязательно для заполнения.',
                'title.max' => 'Поле "Название" не должно превышать :max символов.',
                'title.unique' => 'Поле "Название" должно быть уникальным.',
                'comment.max' => 'Поле "Комментарий" не должно превышать :max символов.',
                'theory.required' => 'Поле "Теория" обязательно для заполнения.',
                'task.required' => 'Поле "Задача" обязательно для заполнения.',
                'stat.required' => 'Поле "Тип" обязательно для заполнения.',
                'stat.in' => 'Поле "Тип" должно быть одним из следующих: theory, practice.',
                'status.required' => 'Поле "Статус" обязательно для заполнения.',
                'status.in' => 'Поле "Статус" должно быть одним из следующих: necessarily, not necessary.',
                'course_id.required' => 'Поле "Курс" обязательно для заполнения.',
                'course_id.exists' => 'Выбранный курс не существует.',
            ]);
    }
}
