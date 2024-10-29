@extends('includes.layout')

@section('content')
    <img src="{{ asset('images/xloading.gif') }}" alt="" class="absolute mt-[-300px] top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
    <div class="fixed inset-0 flex items-center justify-center">
        <div id="registerForm" class="bg-white p-8 rounded-lg shadow-lg w-1/3">
            <h2 class="text-2xl font-bold mb-6">Регистрация</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('email')
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
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Подтвердите пароль</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="block w-full border-gray-300 rounded-md shadow-sm">
                    @error('password_confirmation')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md">Зарегистрироваться</button>
            </form>
            <div class="mt-4 text-center">
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Уже есть аккаунт? Войти</a>
            </div>
        </div>
    </div>
@endsection
