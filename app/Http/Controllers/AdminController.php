<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Database;
use App\Models\FileZilla;
use App\Models\Group;
use App\Models\Module;
use App\Models\Request as RequestModel;
use App\Models\Subdomain;
use App\Models\Task;
use App\Models\TeacherCourseGroup;
use App\Models\User;
use App\Services\BegetAPIService;
use App\Services\BegetDatabaseService;
use App\Services\CourseService;
use App\Services\FileZillaService;
use App\Services\GroupService;
use App\Services\HelperService;
use App\Services\ModuleService;
use App\Services\SubdomainService;
use App\Services\TelegramService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Сервис для работы с API Beget (хостинг).
     * @var BegetAPIService
     */
    protected BegetAPIService $begetApiService;

    /**
     * Сервис для взаимодействия с Telegram ботом.
     * @var TelegramService
     */
    protected TelegramService $telegramService;

    /**
     * Сервис для управления поддоменами.
     * @var SubdomainService
     */
    protected SubdomainService $subdomainService;

    /**
     * Сервис для управления базой данных Beget.
     * @var BegetDatabaseService
     */
    protected BegetDatabaseService $begetDatabaseService;

    /**
     * Сервис для управления FileZilla Beget.
     * @var FileZillaService
     */
    protected FileZillaService $fileZillaService;

    /**
     * Сервис для управления пользователем.
     * @var UserService
     */
    protected UserService $userService;

    protected ModuleService $moduleService;
    protected HelperService $helperService;
    protected CourseService $courseService;
    protected GroupService $groupService;

    /**
     * Символ, отображающий успешное выполнение операции.
     * @var string
     */
    private string $completed = '✅';

    /**
     * Символ, отображающий ошибку при выполнении операции.
     * @var string
     */
    private string $error = '🚫';

    /**
     * Telegram username текущего пользователя.
     * @var ?string
     */
    private ?string $telegramUsername;

    /**
     * Конструктор, в котором происходит инъекция зависимостей.
     *
     * @param BegetAPIService $begetApiService Сервис для работы с API Beget.
     * @param TelegramService $telegramService Сервис для работы с Telegram.
     * @param SubdomainService $subdomainService Сервис для управления поддоменами.
     * @param BegetDatabaseService $begetDatabaseService Сервис для управления базой данных Beget.
     * @param FileZillaService $fileZillaService Сервис для управления FileZilla Beget.
     * @param UserService $userService Сервис для управления пользователем.
     */
    public function __construct(
        BegetAPIService      $begetApiService,
        TelegramService      $telegramService,
        SubdomainService     $subdomainService,
        BegetDatabaseService $begetDatabaseService,
        FileZillaService     $fileZillaService,
        UserService          $userService,
        ModuleService        $moduleService,
        HelperService        $helperService,
        CourseService        $courseService,
        GroupService         $groupService,
    )
    {
        $this->begetApiService = $begetApiService;
        $this->subdomainService = $subdomainService;
        $this->telegramService = $telegramService;
        $this->begetDatabaseService = $begetDatabaseService;
        $this->fileZillaService = $fileZillaService;
        $this->userService = $userService;
        $this->moduleService = $moduleService;
        $this->helperService = $helperService;
        $this->courseService = $courseService;
        $this->groupService = $groupService;
    }


    public function showMain()
    {
        return view('page.admin.main');
    }

    public function showGenerate()
    {
        $groups = Group::all();
        return view('page.admin.generate', compact('groups'));
    }

    public function createUser(Request $request)
    {
        $user = $this->userService->createUser($request);

        if (!is_array($user)) {
            return $this->helperService->returnBackWithError('Ошибка создания пользователя.');
        }

        $login = $user['login'];
        $role = $user['user']->role;
        $userID = $user['user']->id;

        if ($role === 'student') {
            $createDomain = $this->begetApiService->createSiteAndRetrieveIds($login);

            if ($createDomain['status'] === 'success') {
                $this->fileZillaService->createFileZilla([
                    'host' => 'k1z1nksb.beget.tech',
                    'username' => $createDomain['ftPLogin'],
                    'password' => $createDomain['ftPPassword'],
                    'user_id' => $userID,
                ]);

                $this->subdomainService->createSubdomain([
                    'title' => $createDomain['link'],
                    'user_id' => $userID,
                ]);

                $this->begetDatabaseService->createBegetDatabase([
                    'username' => $createDomain['dbLogin'],
                    'password' => $createDomain['dbPassword'],
                    'user_id' => $userID,
                ]);

            } else {
                $user['user']->delete();
                return $this->helperService->returnBackWithError('Ошибка при создании сайта и поддомена.');
            }
        }

        return $this->helperService->returnWithSuccess('admin.generate', 'Пользователь успешно создан.');
    }



    public function showList(Request $request)
    {
        // Получаем параметры поиска
        $search = $request->input('search');
        $category = $request->input('category');

        // Запрос к таблице пользователей
        $query = User::query();

        // Применяем фильтр по категории (если категория выбрана)
        if ($category) {
            $query->whereHas('group', function ($q) use ($category) {
                $q->where('id', $category);
            });
        }

        // Применяем фильтр поиска (если задан поисковый запрос)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%' . $search . '%')
                    ->orWhere('surname', 'like', '%' . $search . '%')
                    ->orWhere('patronymic', 'like', '%' . $search . '%');
            });
        }

        // Получаем отфильтрованные результаты
        $list = $query->paginate(10);

        // Извлекаем все уникальные группы из базы данных
        $groups = Group::all(); // Извлечение всех групп, чтобы использовать их в выпадающем списке
        $count = $list->count();
        $allCount = User::count();

        // Возвращаем представление с отфильтрованными пользователями и группами
        return view('page.admin.list', compact('list', 'groups', 'count', 'allCount'));
    }


    public function showCourses()
    {
        $courses = Course::all();
        return view('page.admin.courses', compact('courses'));
    }

    public function showOneCourse($id)
    {
        $course = Course::findOrFail($id);
        $modules = $course->modules;
        return view('page.admin.course', compact('course', 'modules'));
    }

    public function showAddCourse()
    {
        return view('page.admin.add-course');
    }

    public function storeCourse(Request $request)
    {

        $data = $this->courseService->createCourse($request);

        $logoPath = $this->helperService->uploadFile($request, 'logo', 'courses/logos');

        if ($logoPath) {
            $data['logo'] = $logoPath;
        } else {
            return $this->helperService->returnBackWithError('Ошибка загрузки файла');
        }

        Course::create($data);

        return $this->helperService->returnWithSuccess('admin.courses', 'Курс успешно добавлен');
    }

    public function showAddGroup()
    {
        return view('page.admin.add-group');
    }

    public function storeGroup(Request $request)
    {
        $data = $this->groupService->createGroup($request);

        Group::create($data);

        $this->telegramService->sendMessageToUsername($this->userService->getTelegramUsername(), 'ADMIN: ' . "группа " . $request->input('title') . " создана");

        return $this->helperService->returnWithSuccess('admin.groups', "Группа " . $request->input('title') . " успешно добавлена");
    }

    public function deleteGroup($id)
    {
        $group = Group::findOrFail($id);

        $title = $group->title;

        $group->delete();

        $this->telegramService->sendMessageToUsername($this->userService->getTelegramUsername(), 'ADMIN: ' . "группа " . $title . " удалена");

        return $this->helperService->returnWithSuccess('admin.groups', 'Группа успешно удалена');
    }

    public function updateGroup(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $data = $this->groupService->updateGroup($request, $id);

        $group->update($data);

        $this->telegramService->sendMessageToUsername($this->userService->getTelegramUsername(), 'ADMIN: ' . "группа " . $request->input('title') . " обновлена");

        return $this->helperService->returnWithSuccess('admin.groups', 'Группа успешно обновлена');
    }

    public function showGroups()
    {
        $groups = Group::all();
        return view('page.admin.groups', compact('groups'));
    }

    public function showAddModule($id)
    {
        return view('page.admin.add-module', compact('id'));
    }

    public function storeModule(Request $request)
    {
        $data = $this->moduleService->createModule($request);

        Module::create($data);

        $courseId = $data['course_id'];

        return $this->helperService->returnWithSuccess('admin.show.course', 'Модуль успешно добавлен', $courseId);
    }

    public function editModule($id)
    {
        $module = Module::findOrFail($id);
        return view('page.admin.edit-module', compact('module'));
    }

    public function updateModule(Request $request, $id)
    {

        $module = Module::findOrFail($id);

        $data = $this->moduleService->updateModule($request, $module);

        $module->update($data);

        $courseId = $data['course_id'];

        return $this->helperService->returnWithSuccess('admin.show.course', 'Модуль успешно обновлен', $courseId);
    }

    public function destroyModule($id)
    {
        $module = Module::findOrFail($id);
        $courseId = $module->course;
        $module->delete();

        return $this->helperService->returnWithSuccess('admin.show.course', 'Модуль успешно удален', $courseId);
    }

    public function showRequests()
    {
        $requests = RequestModel::orderByRaw("status = 'pending' desc")->orderBy('status', 'asc')->paginate(10);
        foreach ($requests as $item) {
            $userId = $item->user_id;
            $user = User::where('id', $userId)->first();
            $group = $user->group->title;
            $item['group'] = $group;
        }
        return view('page.admin.requests', compact('requests'));
    }

    public function updateRequest(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,accepted,rejected',
        ]);

        $requestModel = RequestModel::findOrFail($id);

        $requestModel->status = $validatedData['status'];
        $requestModel->save();

        return redirect()->back()->with('success', 'Статус заявки успешно обновлен.');
    }

    public function showTasks()
    {
        $tasks = Task::orderByRaw("FIELD(status, 'pending', 'failed', 'completed')")->paginate(10);

        return view('page.admin.tasks', compact('tasks'));
    }

    public function showTeachers()
    {
        $assignedTeachers = DB::table('teacher_course_groups')
            ->join('users', 'teacher_course_groups.teacher_id', '=', 'users.id')
            ->join('courses', 'teacher_course_groups.course_id', '=', 'courses.id')
            ->join('groups', 'teacher_course_groups.group_id', '=', 'groups.id')
            ->select(
                'users.username',
                'users.surname',
                'users.id as teacher_id',
                'courses.id as course_id',
                'groups.id as group_id',
                'groups.title as group_title',
                'courses.title as course_title'
            )
            ->get();
        $teachers = User::where('role', 'teacher')->get();
        $groups = Group::all();
        $courses = Course::all();
        return view('page.admin.teachers', compact('groups', 'courses', 'teachers', 'assignedTeachers'));
    }

    public function getCoursesForUsers($groupId, $teacherId)
    {
        $courses = Course::whereHas('requests', function ($query) use ($groupId) {
            $query->whereHas('user', function ($subQuery) use ($groupId) {
                $subQuery->where('group_id', $groupId);
            });
        })->with(['requests' => function ($query) use ($groupId) {
            $query->whereHas('user', function ($subQuery) use ($groupId) {
                $subQuery->where('group_id', $groupId);
            });
        }])->get();

        // Получаем существующие связи для преподавателя
        $existingAssignments = DB::table('teacher_course_groups')
            ->where('teacher_id', $teacherId)
            ->where('group_id', $groupId)
            ->pluck('course_id')
            ->toArray();

        // Возвращаем список курсов и отмеченные курсы
        return response()->json([
            'courses' => $courses,
            'existingAssignments' => $existingAssignments
        ]);
    }

    public function assignTeacherToGroupAndCourses(Request $request)
    {
        $teacherId = $request->input('teacher');
        $groupId = $request->input('group');
        $courseIds = $request->input('courses', []);

        if ($teacherId && $groupId && !empty($courseIds)) {
            foreach ($courseIds as $courseId) {
                TeacherCourseGroup::create([
                    'teacher_id' => $teacherId,
                    'course_id' => $courseId,
                    'group_id' => $groupId,
                ]);
            }
            return redirect()->back()->with('success', 'Связи успешно созданы.');
        } else {
            return redirect()->back()->with('error', 'Ошибка, проверьте заполненность полей.');
        }
    }

    public function setting()
    {
        return view('page.admin.setting');
    }

    public function updateTelegramUserName(Request $request)
    {
        $userId = auth()->id();
        $this->telegramService->updateTelegramUsers();
        $isUpdate = $this->userService->updateTelegramUserName($request, $userId);
        if($isUpdate){
            $this->helperService->returnWithSuccess('admin.setting', 'Ник успешно обновлен.');
        }else{
            $this->helperService->returnBackWithError('Не удалось обновить ник;');
        }
    }
}
