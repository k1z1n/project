@extends('includes.layout')
@section('h2-name', 'Генерация пользователя')
@section('content')
    <div>
        <form class="max-w-sm mx-auto bg-white p-5 rounded-lg shadow-md" method="post"
              action="{{ route('admin.generate.store') }}">
            @csrf
            <div class="mb-5">
                <label for="username"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Имя</label>
                <input type="text" id="username" value="{{ old('username') }}" name="username"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Иванов"/>
                @error('username')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-5">
                <label for="surname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Фамилия</label>
                <input type="text" id="surname" value="{{ old('surname') }}" name="surname"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Иван"/>
                @error('surname')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-5">
                <label for="patronymic"
                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Отчество</label>
                <input type="text" id="patronymic" value="{{ old('patronymic') }}" name="patronymic"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       placeholder="Иванович"/>
                @error('patronymic')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-5">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Роль</label>
                <div class="flex items-center mb-4">
                    <input id="role-student" type="radio" name="role" value="student" {{ old('role') == 'student' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-student" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Студент</label>
                </div>
                <div class="flex items-center mb-4">
                    <input id="role-admin" type="radio" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-admin" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Администратор</label>
                </div>
                <div class="flex items-center">
                    <input id="role-teacher" type="radio" name="role" value="teacher" {{ old('role') == 'teacher' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="role-teacher" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Учитель</label>
                </div>
                @error('role')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-5" id="group-select-container" style="display: {{ old('role') == 'student' ? 'block' : 'none' }};">
                <select name="group_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" selected>Выбрать группу</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                    @endforeach
                </select>
                @error('group_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <input type="hidden" name="role" id="role-hidden" value="">
            <button type="submit"
                    class="text-white bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Генерация
            </button>
        </form>
    </div>
    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            const roleRadios = document.querySelectorAll('input[name="role"]');
            const roleHidden = document.getElementById('role-hidden');
            let groupSelectContainer = document.getElementById('group-select-container');

            roleRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    roleHidden.value = this.value;
                    if (this.value === 'student') {
                        groupSelectContainer.style.display = 'block';
                    } else {
                        groupSelectContainer.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
