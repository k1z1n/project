@extends('includes.layout')
@section('h2-name', 'Модуль: ' . $module->title)
@section('content')
    <div class="flex w-full items-center mb-6">
        <h2 class="text-xl ml-5 font-bold flex-shrink-0">Директория для загрузки</h2>
        @if(isset($module->comment))
            <div class="rounded-xl bg-white p-2 w-full ml-5">
                {{ $module->comment }}
            </div>
        @endif
    </div>
    @if(isset($task) && count($comments)>0)
        <h2 class="text-xl ml-5 font-bold flex-shrink-0 mb-3">Комментарии</h2>
        <div class="flex flex-col p-6 bg-white mb-10 rounded-xl">
            @foreach($comments as $item)
                <div class="flex justify-between {{ $loop->last ? '' : 'border-b border-gray-200' }}">
                    <p class="p-2">{{ $item->text }}</p>
                    <p class="text-gray-500 p-2">{{ $item->formatted_created_at }}</p>
                </div>
            @endforeach
        </div>
    @endif
    <div class="bg-white rounded-xl">
        <div id="accordion-collapse-descriptions" data-accordion="collapse" class="mb-10">
            <!-- for loop -->
            <h4 id="accordion-collapse-heading-for-description">
                <button type="button"
                        class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                        data-accordion-target="#accordion-collapse-body-for-description"
                        aria-expanded="false"
                        aria-controls="accordion-collapse-body-for-description">
                    <span>Теория</span>
                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M9 5 5 1 1 5"/>
                    </svg>
                </button>
            </h4>
            <div id="accordion-collapse-body-for-description" class="hidden"
                 aria-labelledby="accordion-collapse-heading-for-description">
                <div
                    class="p-5 max-w-full gap-5 border border-t-0 border-gray-200 dark:border-gray-700 flex flex-wrap">
                    <div class="w-full">
                        <div class="ql-editor">
                            {!! $module->theory !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl">
        <div class="bg-white rounded-xl">
            <div id="accordion-collapse-otvet" data-accordion="collapse" class="mb-10">
                <!-- for loop -->
                <h4 id="accordion-collapse-heading-for-otvet">
                    <button type="button"
                            class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                            data-accordion-target="#accordion-collapse-body-for-otvet"
                            aria-expanded="false"
                            aria-controls="accordion-collapse-body-for-otvet">
                        <span>Задания для выполнения</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="M9 5 5 1 1 5"/>
                        </svg>
                    </button>
                </h4>
                <div id="accordion-collapse-body-for-otvet" class="hidden"
                     aria-labelledby="accordion-collapse-heading-for-otvet">
                    <div
                        class="p-5 max-w-full gap-5 border border-t-0 border-gray-200 dark:border-gray-700 flex flex-wrap">
                        <div class="w-full">
                            <div class="ql-editor">
                                {!! $module->task !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center">
        @if(!isset($task) || $task->status === 'failed')
            <form action="{{ route('student.task.create', $module->id) }}" method="post">
                @csrf
                <input type="submit" value="Отметить выполнение"
                       class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            </form>
        @else
        @endif
        @if(isset($task))
            <div class="
            @if($task->formatted_status === 'В ожидании проверки')
            bg-yellow-100
                                text-black
             @elseif($task->formatted_status === 'Выполнено')
             bg-green-500 text-white
              @elseif($task->formatted_status === 'Ошибка выполнения')
              bg-red-500 text-white
              @endif
            bg-yellow-50 rounded-lg py-2 px-4 text-yellow-800">
                {{ $task->formatted_status }}
            </div>
        @endif
    </div>
@endsection
