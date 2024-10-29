<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserService
{

    private ?string $telegramUsername;

    public function __construct()
    {
        $this->telegramUsername = auth()->check() ?? auth()->user()->telegram_username;
    }


    public function getTelegramUsername(): ?string
    {
        return $this->telegramUsername;
    }

    public function createUser($request): false|array
    {
        $validatedData = $this->validateCreateUser($request);

        $baseLogin = collect(['username', 'patronymic'])
            ->map(fn($field) => Str::slug(Str::substr($request->input($field), 0, 2)))
            ->implode('');

        $login = $baseLogin;
        $counter = 1;

        while (User::where('login', $login)->exists()) {
            $login = $baseLogin . $counter;
            $counter++;
        }

        $password = Str::random(8);

        try {
            $user = User::create([
                'username' => $validatedData['username'],
                'surname' => $validatedData['surname'],
                'patronymic' => $validatedData['patronymic'],
                'login' => $login,
                'group_id' => $validatedData['role'] === 'student' ? $validatedData['group_id'] : null,
                'pp' => $password,
                'password' => Hash::make($password),
                'role' => $validatedData['role'],
            ]);

            return [
                'login' => $login,
                'user' => $user
            ];
        } catch (\Exception $error) {
            Log::error('Failed to create user: ' . $error->getMessage());
            return false;
        }
    }

    private function validateCreateUser($request)
    {
        return $request->validate([
            'username' => 'required|max:255',
            'surname' => 'required|max:255',
            'patronymic' => 'required|max:255',
            'group_id' => 'required_if:role,student',
            'role' => 'required|in:student,admin,teacher',
        ], [
            'username.required' => 'Имя обязательно для заполнения.',
            'surname.required' => 'Фамилия обязательна для заполнения.',
            'patronymic.required' => 'Отчество обязательно для заполнения.',
            'group_id.required_if' => 'Группа обязательна для заполнения, если выбрана роль "Студент".',
            'role.required' => 'Роль обязательна для заполнения.',
            'role.in' => 'Недопустимая роль.',
        ]);
    }

    public function validateLoginUser($request)
    {
        return $request->validate([
            'login' => 'required',
            'password' => 'required'
        ],
            [
                'login.required' => 'Поле логин обязательно для заполнения.',
                'password.required' => 'Поле пароль обязательно для заполнения.'
            ]);
    }

    public function redirectDuringLogin($role): ?string
    {
        return match ($role) {
            'student' => route('student.main'),
            'teacher' => route('teacher.main'),
            'admin', 'superadmin' => route('admin.list'),
            default => null,
        };
    }

    public function updateTelegramUserName($request, $id)
    {
        $data = $this->validateTelegramUsername($request, $id);

        $user = User::findOrFail($id);

        $user->telegram_username = $data['telegram_username'];

        return $user->save();
    }

    private function validateTelegramUsername($request, $id)
    {
        return $request->validate([
            'telegram_username' => [
                'required',
                'min:5',
                'max:255',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($id),
            ],
        ], [
            'telegram_username.required' => 'Поле "Telegram Username" обязательно для заполнения.',
            'telegram_username.min' => 'Поле "Telegram Username" должно содержать минимум :min символов.',
            'telegram_username.max' => 'Поле "Telegram Username" должно содержать не более :max символов.',
            'telegram_username.regex' => 'Поле "Telegram Username" может содержать только буквы, цифры и нижнее подчеркивание.',
            'telegram_username.unique' => 'Такой Telegram Username уже существует.',
        ]);
    }
}
