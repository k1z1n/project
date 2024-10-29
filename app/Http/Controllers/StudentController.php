<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Module;
use App\Models\Request as RequestModel;
use App\Models\Task;
use App\Services\CourseService;
use App\Services\HelperService;
use App\Services\ModuleService;
use App\Services\TaskService;
use App\Services\TelegramService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    protected TaskService $taskService;
    protected CourseService $courseService;
    protected ModuleService $moduleService;
    protected HelperService $helperService;

    protected UserService $userService;
    protected TelegramService $telegramService;

    public function __construct(
        TaskService     $taskService,
        CourseService   $courseService,
        ModuleService   $moduleService,
        HelperService   $helperService,
        UserService     $userService,
        TelegramService $telegramService
    )
    {
        $this->taskService = $taskService;
        $this->courseService = $courseService;
        $this->moduleService = $moduleService;
        $this->helperService = $helperService;
        $this->userService = $userService;
        $this->telegramService = $telegramService;
    }

    public function showMain()
    {
        $userId = auth()->id();
        $tasks = $this->taskService->getTasksForUser($userId);
        $courses = $this->courseService->getCoursesForUser($userId);
        foreach ($courses as $course) {
            $course->progress = $this->courseService->calculateCourseProgress($course, $userId);
        }

        $courses = $courses->sortBy('progress');

        return view('page.student.main', compact('courses', 'tasks'));
    }

    public function loadMoreHistory(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $userId = auth()->id();

        $tasks = Task::where('user_id', $userId)
            ->with('module.course') // Подключаем связанные таблицы
            ->orderByRaw("FIELD(status, 'failed', 'pending', 'completed')") // Сортировка по статусу
            ->orderBy('updated_at', 'desc') // Сортировка по времени изменения
            ->offset($offset)
            ->limit($limit)
            ->get();

        $tasks->transform(function ($task) {
            $task->status = $this->taskService->translateStatus($task->status);
            return $task;
        });
        return response()->json($tasks);
    }

    public function showSettings()
    {
        return view('page.student.settings');
    }

    public function showCourses()
    {
        $userId = auth()->id();
        $courses = Course::all();

        foreach ($courses as $course) {
            $request = RequestModel::where('course_id', $course->id)->where('user_id', $userId)->first();
            if (isset($request) && $request->status === "accepted") {
                $course->progress = $this->courseService->calculateCourseProgress($course, $userId);
            }else{
                $course->progress = null;
            }
        }

        return view('page.student.courses', compact('courses'));
    }


    public function showSetting()
    {
        return view('page.student.setting');
    }

    public function showOneCourse($id)
    {
        $course = Course::findOrFail($id);
        $userId = auth()->id();
        $request = RequestModel::where('user_id', $userId)->where('course_id', $course->id)->first();
        if (isset($request) && $request->status === "accepted") {
            $course->progress = $this->courseService->calculateCourseProgress($course, $userId);
        }

        $modules = $course->modules;

        foreach($modules as $module) {
            $task = $this->taskService->getTasksByModule($module->id, $userId)->first();
            if (!is_null($task)) {
                $module['status_and'] = $this->taskService->translateStatus($task->status);
            }
        }

        return view('page.student.one-course', compact('course', 'request', 'modules'));
    }

    public function showOneModule($id)
    {
        $userId = auth()->id();
        $module = $this->moduleService->getModuleById($id);
        $task = $this->taskService->getTasksByModule($module->id, $userId)->first();

        if ($task) {
            $comments = $task->comments;
            $comments = $this->helperService->formatDate($comments);
            $task->formatted_status = $this->taskService->translateStatus($task->status);
            return view('page.student.one-module', compact('module', 'task', 'comments'));
        }

        return view('page.student.one-module', compact('module'));
    }

    public function updateTelegramUserName(Request $request)
    {
        $userId = auth()->id();
        $this->telegramService->updateTelegramUsers();
        $isUpdate = $this->userService->updateTelegramUserName($request, $userId);
        if ($isUpdate) {
           return $this->helperService->returnWithSuccess('student.setting', 'Ник успешно обновлен.');
        } else {
           return $this->helperService->returnBackWithError('Не удалось обновить ник;');
        }
    }

    public function showTheory()
    {
        return view('page.student.theory');
    }

    public function showModules()
    {
        return view('page.student.modules');
    }
}

