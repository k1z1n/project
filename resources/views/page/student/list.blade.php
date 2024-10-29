@extends('includes.layout')
@section('h2-name', 'Главная')
@section('content')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Список Контента</h1>

        @foreach($lists as $content)
            <div class="">
                <h2 class="text-xl font-semibold">Контент {{ $content->id }}</h2>
                <!-- Используем классы Tailwind и Quill -->
                <div class="">
                    <div class="ql-editor">
                        {!! $content->mixed_content !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
