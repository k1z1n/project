@extends('includes.layout')

@section('h2-name', 'Доступные курсы')

@section('content')
    <div class="grid grid-cols-3 gap-5">
        @foreach($courses as $course)
            <a href="{{ route('student.one.course', $course->id) }}" class="course-item bg-white rounded-xl p-3 flex flex-col items-center gap-3 justify-center h-full relative">
                <img src="{{ asset('storage/' . $course->logo) }}" alt="" class="object-cover h-full max-h-36">
                <h4 class="text-md">{{ $course->title }}</h4>
                @if ($course['progress'] !== null)
                    <div class="progress-circle w-10 h-10 absolute right-2 top-2" id="progress-circle-{{ $course->id }}" data-progress="{{ $course['progress'] }}"></div>
                @endif
            </a>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/progressbar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressCircles = document.querySelectorAll('.progress-circle');

            progressCircles.forEach(circle => {
                const progress = parseFloat(circle.getAttribute('data-progress'));

                if (!isNaN(progress)) {
                    const bar = new ProgressBar.Circle(circle, {
                        color: 'rgb(10,10,255)', // Изменение цвета линии на #1d4ed8
                        strokeWidth: 4,
                        trailWidth: 20,
                        easing: 'easeInOut',
                        duration: 1400,
                        text: {
                            autoStyleContainer: false
                        },
                        from: { color: '#aaa', width: 20 },
                        to: { color: 'rgb(40,193,85)', width: 20 }, // Изменение цвета линии на #1d4ed8
                        step: function(state, circle) {
                            circle.path.setAttribute('stroke', state.color);
                            circle.path.setAttribute('stroke-width', state.width);

                            const value = Math.round(circle.value() * 100);
                            if (value === 0) {
                                circle.setText('');
                            } else if (value === 100) {
                                circle.setText(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgb(40,193,85)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check" style="width: 100%; height: 100%;"><path d="M20 6L9 17l-5-5"></path></svg>`);
                            }
                        }
                    });

                    bar.text.style.fontFamily = '"Raleway", Helvetica, sans-serif';
                    bar.text.style.fontSize = '1rem';

                    bar.animate(progress / 100);
                }
            });
        });
    </script>
@endsection
