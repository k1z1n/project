@extends('includes.layout')
@section('h2-name', 'Курсы')
@section('content')
    <div class="grid grid-cols-3 gap-5">
        @foreach($courses as $course)
        <a href="{{ route('admin.show.course', $course->id) }}" class="bg-white rounded-xl p-3 flex flex-col items-center gap-3 justify-center h-full">
            <img src="{{ asset('storage/' . $course->logo) }}" alt="" class="object-cover h-full max-h-36">
            <h4 class="text-md">{{ $course->title }}</h4>
        </a>
        @endforeach
    </div>
@endsection
