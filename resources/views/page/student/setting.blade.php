@extends('includes.layout')
@section('h2-name', 'Настройки')
@section('content')
    <div class="flex gap-5 flex-col">
        <div class="bg-white rounded-xl px-5 pt-5 pb-4 flex flex-col">
            <h2 class="text-xl mb-4">Сменить аватар</h2>
            <div class="flex"></div>
            <img src="{{ asset('images/user.png') }}" alt="" class="w-16 flex justify-center mb-4">
            <form action="" method="post">
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    aria-describedby="file_input_help" id="file_input" type="file">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG
                    or GIF (MAX. 800x400px).</p>
                <input type="submit"
                       class="bg-gray-900 mt-3 cursor-pointer text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                       value="Сменить">
            </form>
        </div>
        <div class="bg-white rounded-xl px-5 pt-5 pb-4 flex flex-col justify-between relative">
            <h2 class="text-xl mb-4">Сменить ник телеграм</h2>
            <form action="{{ route('student.setting.update.telegram') }}" method="post">
                @csrf
                <label for="telegram_username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@if(isset(auth()->user()->telegram_username)) Тукущий ник: {{ auth()->user()->telegram_username }}  @else Ник @endif</label>
                <input type="text" id="telegram_username" name="telegram_username"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('telegram_username') border-red-500 @enderror"
                       placeholder="Введите ник телеграм" value="{{ old('telegram_username') }}"/>

                @error('telegram_username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">Указывайте корректный ник (без @)</p>
                <input type="submit"
                       class="bg-gray-900 mt-3 cursor-pointer text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                       value="Сменить">
            </form>
            <div class="absolute top-5 right-3">
                <button type="button" data-modal-target="crypto-modal" data-modal-toggle="crypto-modal">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div id="crypto-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Подключение уведомления
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crypto-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Для старта получения уведомлений нажать кнопку 'start' у бота перед тем как сменить ник.</p>
                    <ul class="my-4 space-y-3">
                        <li>
                            <a href="https://t.me/kplatforma_bot" class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                <i class='bx bxl-telegram'></i>
                                <span class="flex-1 ms-3 whitespace-nowrap">Telegram bot</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


@endsection
