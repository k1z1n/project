@extends('includes.layout')

@section('content')
    <div class="mx-auto font-bold bg-white py-4 text-center">
        <h1 class="text-2xl">Регистрация данных</h1>
    </div>

    <style>
        .dot {
            transition: transform 0.3s ease;
        }

        input:checked ~ .dot {
            transform: translateX(100%);
        }
    </style>
    <div class="w-1/3 mx-auto mt-5">
        <div class="flex justify-center mb-4">
            <label for="toggleB" class="flex items-center cursor-pointer">
                <div class="mr-3 text-gray-700 font-medium">Студент</div>
                <div class="relative">
                    <input type="checkbox" id="toggleB" class="sr-only" onclick="toggleForm()">
                    <div class="block bg-gray-600 w-14 h-8 rounded-full"></div>
                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition"></div>
                </div>
                <div class="ml-3 text-gray-700 font-medium">Преподователь</div>
            </label>
        </div>

        <div id="studentForm" class="bg-white p-8 rounded-lg shadow-lg mb-20">
            <h2 class="text-xl font-bold mb-6">Регистрация студента</h2>
            <form method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="student_name" class="block text-gray-700 font-medium mb-2">Имя</label>
                    <input type="text" id="student_name" name="student_name" value="{{ old('student_name') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_surname" class="block text-gray-700 font-medium mb-2">Фамилия</label>
                    <input type="text" id="student_surname" name="student_surname" value="{{ old('student_surname') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_surname')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_patronymic" class="block text-gray-700 font-medium mb-2">Отчество</label>
                    <input type="text" id="student_patronymic" name="student_patronymic" value="{{ old('student_patronymic') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_patronymic')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="student_email" name="student_email" value="{{ old('student_email') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_group" class="block text-gray-700 font-medium mb-2">Номер группы</label>
                    <input type="number" id="student_group" name="student_group" value="{{ old('student_group') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_group')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_year" class="block text-gray-700 font-medium mb-2">Год поступления</label>
                    <input type="number" id="student_year" name="student_year" value="{{ old('student_year') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_year')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_login" class="block text-gray-700 font-medium mb-2">Логин для входа</label>
                    <input type="text" id="student_login" name="student_login" value="{{ old('student_login') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_login')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="student_password" class="block text-gray-700 font-medium mb-2">Пароль для входа</label>
                    <input type="password" id="student_password" name="student_password" value="{{ old('student_password') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('student_password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Подтвердить</button>
            </form>
        </div>

        <div id="teacherForm" class="bg-white p-8 rounded-lg shadow-lg hidden">
            <h2 class="text-xl font-bold mb-6">Регистрация преподователя</h2>
            <form method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="teacher_name" class="block text-gray-700 font-medium mb-2">Имя</label>
                    <input type="text" id="teacher_name" name="teacher_name" value="{{ old('teacher_name') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('teacher_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="teacher_surname" class="block text-gray-700 font-medium mb-2">Фамилия</label>
                    <input type="text" id="teacher_surname" name="teacher_surname" value="{{ old('teacher_surname') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('teacher_surname')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="teacher_patronymic" class="block text-gray-700 font-medium mb-2">Отчество</label>
                    <input type="text" id="teacher_patronymic" name="teacher_patronymic" value="{{ old('teacher_patronymic') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('teacher_patronymic')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="teacher_email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="teacher_email" name="teacher_email" value="{{ old('teacher_email') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('teacher_email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="teacher_login" class="block text-gray-700 font-medium mb-2">Login для входа</label>
                    <input type="text" id="teacher_login" name="teacher_login" value="{{ old('teacher_login') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('teacher_login')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="teacher_password" class="block text-gray-700 font-medium mb-2">Пароль</label>
                    <input type="password" id="teacher_password" name="teacher_password" value="{{ old('teacher_password') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('teacher_password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Подтвердить</button>
            </form>
        </div>
    </div>

    <script>
        function toggleForm() {
            var studentForm = document.getElementById('studentForm');
            var teacherForm = document.getElementById('teacherForm');
            var toggle = document.getElementById('toggleB');

            if (toggle.checked) {
                studentForm.classList.add('hidden');
                teacherForm.classList.remove('hidden');
            } else {
                studentForm.classList.remove('hidden');
                teacherForm.classList.add('hidden');
            }
        }
    </script>
@endsection
