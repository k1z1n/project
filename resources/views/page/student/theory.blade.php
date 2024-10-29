@extends('includes.layout')
@section('h2-name', 'Теория')
@section('content')
    <a href="{{ route('student.theory.modules') }}" class="flex items-center w-full justify-between bg-white rounded-md p-5">
        <p>PHP</p>
        <img src="{{ asset('/images/php.png') }}" alt="" class="w-1/12">

    </a>
@endsection
