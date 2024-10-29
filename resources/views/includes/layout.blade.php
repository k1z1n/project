<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Include Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link type="image/x-icon" rel="shortcut icon" href="{{ asset('icons/infinity_3Hl_icon.ico') }}">
    <title>{{ config('app.name') }}</title>
</head>
<body>
<script defer>
    document.addEventListener('DOMContentLoaded', function () {
        const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');
        const menuBar = document.querySelector('.content nav .bx.bx-menu');
        const sideBar = document.querySelector('.sidebar');
        if (sideBar) {
            sideBar.style.opacity = '0';
            sideBar.style.visibility = 'hidden';
        }

        // Проверка состояния меню при загрузке страницы
        // Скрываем меню до завершения проверки состояния
        sideBar.style.opacity = '0';
        sideBar.style.visibility = 'hidden';

        // Проверка состояния меню при загрузке страницы
        if (localStorage.getItem('sidebarClosed') === 'true') {
            sideBar.classList.add('close');
        } else {
            sideBar.classList.remove('close');
        }

        // Показываем меню после завершения проверки состояния
        sideBar.style.opacity = '1';
        sideBar.style.visibility = 'visible';

        // Добавление события клика для открытия/закрытия меню
        menuBar.addEventListener('click', () => {
            sideBar.classList.toggle('close');
            // Сохранение состояния меню в localStorage
            localStorage.setItem('sidebarClosed', sideBar.classList.contains('close'));
        });

        sideLinks.forEach(item => {
            const li = item.parentElement;
            item.addEventListener('click', () => {
                sideLinks.forEach(i => {
                    i.parentElement.classList.remove('active');
                })
                li.classList.add('active');
            })
        });

        const searchBtn = document.querySelector('.content nav form .form-input button');
        const searchBtnIcon = document.querySelector('.content nav form .form-input button .bx');
        const searchForm = document.querySelector('.content nav form');

        searchBtn.addEventListener('click', function (e) {
            if (window.innerWidth < 576) {
                e.preventDefault();
                searchForm.classList.toggle('show');
                if (searchForm.classList.contains('show')) {
                    searchBtnIcon.classList.replace('bx-search', 'bx-x');
                } else {
                    searchBtnIcon.classList.replace('bx-x', 'bx-search');
                }
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth < 768) {
                sideBar.classList.add('close');
            } else {
                sideBar.classList.remove('close');
            }
            if (window.innerWidth > 576) {
                searchBtnIcon.classList.replace('bx-x', 'bx-search');
                searchForm.classList.remove('show');
            }
        });

        const toggler = document.getElementById('theme-toggle');

        toggler.addEventListener('change', function () {
            if (this.checked) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        });
    });
</script>
@adminArea
<div class="sidebar">
    <a href="{{ route('admin.list') }}" class="logo">
        <img src="{{ asset('icons/infinity.png') }}" alt="" class="w-10 ml-3 mr-2">
        <div class="logo-name gradient">платформа</div>
    </a>
    <ul class="side-menu">
        <li class="{{ request()->is('admin/list') ? 'active' : '' }}">
            <a href="{{ route('admin.list') }}">
                <i class='bx bxs-dashboard'></i> Главная
            </a>
        </li>
        {{--        <li class="{{ request()->is('admin/list') ? 'active' : '' }}">--}}
        {{--            <a href="{{ route('admin.list') }}">--}}
        {{--                <i class='bx bx-group'></i> Пользователи--}}
        {{--            </a>--}}
        {{--        </li>--}}
        <li class="{{ request()->is('admin/generate') ? 'active' : '' }}">
            <a href="{{ route('admin.generate') }}">
                <i class='bx bx-plus-circle'></i> Генерация
            </a>
        </li>
        <li class="{{ request()->is('admin/add/group') ? 'active' : '' }}">
            <a href="{{ route('admin.add.group') }}">
                <i class='bx bx-plus'></i> Добавить группа
            </a>
        </li>
        <li class="{{ request()->is('admin/groups') ? 'active' : '' }}">
            <a href="{{ route('admin.groups') }}">
                <i class='bx bx-list-ul'></i>Список групп
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <div class="logout">
                <form action="{{ route('logout') }}" method="post" style="display: flex; align-items: center;">
                    @csrf
                    <button type="submit"
                            style="display: flex; align-items: center; border: none; background: none; cursor: pointer;">
                        <i class='bx bx-log-out-circle'></i>
                        Выход
                    </button>
                </form>
            </div>
        </li>
    </ul>
</div>
@endadminArea
@studentArea
<div class="sidebar close">
    <a href="{{ route('student.main') }}" class="logo">
        <img src="{{ asset('icons/infinity.png') }}" alt="" class="w-10 ml-3 mr-2">
        <div class="logo-name gradient">платформа</div>
    </a>
    <ul class="side-menu">
        <li class="{{ request()->is('student') ? 'active' : '' }}">
            <a href="{{ route('student.main') }}">
                <i class='bx bxs-dashboard'></i> Главная
            </a>
        </li>
    </ul>
    <ul class="side-menu">
        <li>
            <div class="logout">
                <form action="{{ route('logout') }}" method="post" style="display: flex; align-items: center;">
                    @csrf
                    <button type="submit"
                            style="display: flex; align-items: center; border: none; background: none; cursor: pointer;">
                        <i class='bx bx-log-out-circle'></i>
                        Выход
                    </button>
                </form>
            </div>
        </li>
    </ul>
</div>
@endstudentArea
<div class="content">
    @auth()
        <nav class="navi">
            <i class='bx bx-menu'></i>
            <form action="#">
                <h2 class="text-xl">@yield('h2-name')</h2>
            </form>
            <a href="#" class="profile">
                <img src="{{ asset('images/user.png') }}" alt="">
            </a>
        </nav>
    @endauth
    <main class="flex gap-5">
        @guest()
            <div class="w-3/4 gap-5">
                @include('includes.message')
                @yield('content')
            </div>
        @endguest
        @adminArea
        <div class="w-full gap-5">
            @include('includes.message')
            @yield('content')
        </div>
        @endadminArea
        @adminArea
        <div class="w-1/4 gap-y-5 flex flex-col">
            <div class="flex rounded-xl bg-white flex-col p-6 items-center">
                <img src="{{ asset('images/user.png') }}" alt="" class="w-16 h-16 mb-4">
                <h3 class="mb-2">{{ auth()->user()->username }} {{ auth()->user()->patronymic }}</h3>
                <p><span class="text-[#677483]">Администратор</span></p>
            </div>
        </div>
        @endadminArea
        @studentArea
        <div class="w-full gap-y-5 flex flex-col">
            <div class="flex rounded-xl bg-white flex-col p-6 items-center">
                <img src="{{ asset('images/user.png') }}" alt="" class="w-16 h-16 mb-4">
                <h3 class="mb-2">{{ auth()->user()->username }} {{ auth()->user()->patronymic }}</h3>
                <p><span class="text-[#677483]">Студент группы: </span>{{ auth()->user()->group->title }}</p>
            </div>
            <div class="flex flex-col gap-y-6 rounded-xl bg-white shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 text-center">Данные личного домена</h3>

                <!-- Контейнер для трех столбцов -->
                <div class="flex flex-col md:flex-row gap-y-6 gap-x-4 w-full">

                    <!-- Первый столбец -->
                    <div class="flex-1 p-4 border rounded-md shadow-sm">
                        <h3 class="font-semibold">Подключение для FileZilla</h3>
                        <div class="mt-2">
                            <p><span class="text-[#677483]">Хост: </span>{{ $fileZilla->host }}</p>
                            <p><span class="text-[#677483]">Имя пользователя: </span>{{ $fileZilla->username }}</p>
                            <p><span class="text-[#677483]">Пароль: </span>{{ $fileZilla->password }}</p>
                        </div>
                    </div>

                    <!-- Второй столбец -->
                    <div class="flex-1 p-4 border rounded-md shadow-sm">
                        <h3 class="font-semibold">Подключение для phpMyAdmin</h3>
                        <div class="mt-2">
                            <p><span class="text-[#677483]">Ссылка: </span><a
                                    href="https://loki.beget.com/phpMyAdmin/index.php">Перейти</a></p>
                            <p><span class="text-[#677483]">Имя пользователя: </span>{{ $database->username }}</p>
                            <p><span class="text-[#677483]">Пароль: </span>{{ $database->password }}</p>
                        </div>
                    </div>

                    <!-- Третий столбец -->
                    <div class="flex-1 p-4 border rounded-md shadow-sm">
                        <h3 class="font-semibold">Данные для платформы</h3>
                        <div class="mt-2">
                            <p><span class="text-[#677483]">Логин: </span>{{ auth()->user()->login }}</p>
                            <p><span class="text-[#677483]">Пароль: </span>{{ auth()->user()->pp }}</p>
                        </div>
                    </div>

                </div>

                <!-- Дополнительная строка с информацией под столбцами -->
                <div class="w-full mt-6 p-4 border-t">
                    <p class="text-center text-gray-700">
                        Дополнительная информация: для доступа к SSH используйте следующий код в терминале br
                        <span class="bg-gray-100 text-gray-800 font-mono px-2 py-1 rounded">
        ssh {{ $fileZilla->username }}@k1z1nksb.beget.tech
                        </span>, далее введите пароль <span
                            class="bg-gray-100 text-gray-800 font-mono px-2 py-1 rounded">{{ $fileZilla->password }}</span>
                    </p>
                </div>
            </div>
        </div>
        @endstudentArea
    </main>
</div>
</body>
</html>
