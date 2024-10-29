@extends('includes.layout')
@section('h2-name', 'Запросы')
@section('content')
        @foreach($requests as $request)
            <div class="flex justify-between items-center bg-white shadow-md rounded-lg p-4 mb-4">
                <div class="grid grid-cols-3 gap-4">
                    <div class="flex justify-start min-w-[250px]">
                        <span class="font-bold">{{ $request->user->username }} {{ $request->user->surname }}</span>
                    </div>
                    <div class="flex justify-start min-w-[100px]">
                        <span>Группа: {{ $request->group }}</span>
                    </div>
                    <div class="flex justify-start min-w-[10px]">
                        <span class="text-gray-600">Курс: {{ $request->course->title }}</span>
                    </div>
                </div>




                <div class="">
                    <form action="{{ route('admin.request.update', $request->id) }}" method="post" class="flex gap-2 items-center m-0">
                        @csrf
                        @method('PUT')
                        <select name="status" id="status" class="px-4 py-2 border rounded-lg">
                            <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Ожидаемый</option>
                            <option value="accepted" {{ $request->status == 'accepted' ? 'selected' : '' }}>Принято</option>
                            <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Отклоненный</option>
                        </select>
                        <input type="submit" value="Изменить" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer">
                    </form>
                </div>
            </div>
        @endforeach
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
@endsection
