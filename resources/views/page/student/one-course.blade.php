@extends('includes.layout')
@section('h2-name', $course->title)
@section('content')
    <div class="flex gap-5">
        <div class="w-full flex gap-5">
            <div class="w-1/4 bg-white rounded-xl flex flex-col items-center p-4 gap-3">
                <img src="{{ asset('storage/' . $course->logo) }}" alt="" class="h-full max-h-36 object-cover">
                <h4 class="text-md">{{ $course->description }}</h4>
            </div>
            <div class="w-3/4 bg-white rounded-xl px-4 pt-4 pb-3 flex flex-col gap-3 justify-between">
                {{ $course->description }}

                @if(isset($request) && $request->status === 'accepted')
                    <form action="">
                        <button disabled
                                class="flex justify-start text-white bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            Выполнено на {{ $course->progress }} %
                        </button>
                    </form>
                @else
                    <form action="{{ route('student.request.record', $course->id) }}" method="post">
                        @csrf
                        <input type="submit"
                               class="cursor-pointer text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                               value="Записаться на курс">
                    </form>
                @endif
            </div>
        </div>
    </div>
    <h2 class="text-xl my-6 ml-5 font-bold">Задания для выполнения</h2>
    <div class="bg-white rounded-xl">
        @foreach($modules as $module)
            @if(isset($request) && $request->status === 'accepted')
                <a href="{{ route('student.one.module', $module->id) }}" class="flex items-center">
                    <div
                        class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3">
                        <span>{{ $module->title }}</span>
                        <div
                            class="cursor-pointer
                                @if($module->status_and === 'В ожидании проверки')
                                bg-yellow-100
                                text-black
                                @elseif($module->status_and === 'Выполнено')
                                bg-green-500 text-white
                                @elseif($module->status_and === 'Ошибка выполнения')
                                bg-red-500 text-white
                                @else
                                bg-blue-700 text-white
                                @endif font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600">
                            @if($module->status_and)
                                {{ $module->status_and }}
                            @else
                                Выполнить
                            @endif
                        </div>
                    </div>
                </a>
            @else
                <a href="#" class="flex items-center">
                    <div
                        class="flex items-center justify-between w-full p-5 font-medium text-gray-500 border border-gray-200 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3">
                        <span>{{ $module->title }}</span>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
@endsection
