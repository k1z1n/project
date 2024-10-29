@extends('includes.layout')

@section('content')
    <img src="{{ asset('images/xloading.gif') }}" alt="" class="absolute mt-[-300px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
    <div class="fixed inset-0 flex items-center justify-center">
        <div id="loginForm" class="bg-white p-8 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-bold mb-6">Авторизация</h2>
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="login" class="block text-gray-700 font-medium mb-2">Логин</label>
                    <input type="text" id="login" name="login" value="{{ old('login') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('login')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Пароль</label>
                    <input type="password" id="password" name="password" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md">Войти</button>
            </form>
{{--            <div class="mt-4 text-center">--}}
{{--                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Нет аккаунта? Зарегистрироваться</a>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection
