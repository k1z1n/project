@extends('includes.layout')
@section('h2-name', 'Список пользователей')
@section('content')
    <div class="flex gap-5">
        <form class="max-w-lg mb-5" method="GET" action="{{ route('admin.list') }}">
            <div class="flex">
                <!-- Dropdown Button for Categories -->
                <button id="dropdown-button" data-dropdown-toggle="dropdown"
                        class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:border-gray-600"
                        type="button">
                    <!-- Display selected category or default text -->
                    <span id="dropdown-text">{{ request('category', 'Все группы') }}</span>
                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdown"
                     class="z-10 hidden left-28 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                        <li>
                            <button type="button"
                                    class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    onclick="setCategory('')">Все группы
                            </button>
                        </li>
                        @foreach($groups as $item)
                            <li>
                                <button type="button"
                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        onclick="setCategory('{{ $item->id }}')">{{ $item->title }}</button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Hidden Input for Selected Category -->
                <input type="hidden" name="category" id="selected-category" value="{{ request('category') }}">

                <!-- Search Input -->
                <div class="relative w-52">
                    <input type="search" id="search-dropdown" name="search"
                           class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                           placeholder="Поиск студента..." value="{{ request('search') }}"/>
                    <button type="submit"
                            class=" bg-gra absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white rounded-e-lg hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                        <span class="sr-only">Поиск</span>
                    </button>
                </div>
            </div>
        </form>
        <a href="{{ route('admin.list') }}"
           class="p-[11px] text-sm text-white font-medium h-full bg-gray-800 rounded-lg dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600">
            Сбросить
        </a>

        <script>
            function setCategory(category) {
                document.getElementById('selected-category').value = category;
                let selectedText = category ? @json($groups->pluck('title', 'id'))[category] : 'Все группы';
                document.getElementById('dropdown-text').innerText = selectedText;
            }

            document.addEventListener('DOMContentLoaded', function () {
                let selectedCategory = document.getElementById('selected-category').value;
                if (selectedCategory) {
                    document.getElementById('dropdown-text').innerText = @json($groups->pluck('title', 'id'))[selectedCategory];
                }
            });
        </script>


    </div>
    <!-- Student List -->
    @if($count > 0)
        @if(request('category') || request('search'))
            <div
                class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium"></span> Найдено записей: {{ $count }}
                </div>
            </div>
        @else
            <p class="mb-4 text-sm text-gray-700 dark:text-gray-200">Всего записей записей: {{ $allCount }}</p>
        @endif

        <!-- Table for displaying the found records -->
        <table class="min-w-full rounded-xl bg-white">
            <thead>
            <tr>
                <th class="py-2 px-4 border-b">Имя</th>
                <th class="py-2 px-4 border-b">Фамилия</th>
                <th class="py-2 px-4 border-b">Отчество</th>
                <th class="py-2 px-4 border-b">Группа</th>
                <th class="py-2 px-4 border-b">Логин и пароль</th>
            </tr>
            </thead>
            <tbody>
            @foreach($list as $student)
                <tr>
                    <td class="py-2 px-4 border-b text-center">{{ $student->username }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $student->surname }}</td>
                    <td class="py-2 px-4 border-b text-center">{{ $student->patronymic }}</td>
                    <td class="py-2 px-4 border-b text-center">
                        @if($student->group_id)
                            {{ $student->group->title }}
                        @else
                            Нет группы
                        @endif
                    </td>
                    <td class="py-2 px-4 border-b">
                        <div class="flex items-center justify-center">
                            <button class="copy-btn" data-login="{{ $student->login }}"
                                    data-password="{{ $student->pp }}">
                                <i class='bx bx-copy'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-4 flex justify-center gap-5">
            {{ $list->appends(request()->input())->links() }}
        </div>
    @else
        <div
            class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                 fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium">Записи не найдены.</span>
            </div>
        </div>
    @endif
    <script>
        async function copyText(text) {
            try {
                await navigator.clipboard.writeText(text);
                console.log('Text copied to clipboard');
            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        }

        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', () => {
                const login = button.getAttribute('data-login');
                const password = button.getAttribute('data-password');
                const textToCopy = `Логин: ${login}\nПароль: ${password}`;

                copyText(textToCopy);
            });
        });
    </script>
@endsection
