@extends('includes.layout')
@section('h2-name', 'Назначить преподавателя')
@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Назначенные преподаватели</h1>
            <button data-modal-target="assignTeacherModal" data-modal-toggle="assignTeacherModal"
                    class="block text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-1.5 text-center">
                Добавить преподавателя
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Преподаватель
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Группа
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Курс
                    </th>
                    <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        действие
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($assignedTeachers as $assignment)
                    <tr>
                        <td class="py-3 px-4 text-sm text-gray-900">
                            {{ $assignment->username }} {{ $assignment->surname }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-900">
                            {{ $assignment->group_title }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-900">
                            {{ $assignment->course_title }}
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-900">
                            <form action="{{ route('admin.assign.remove', ['teacher_id' => $assignment->teacher_id, 'group_id' => $assignment->group_id, 'course_id' => $assignment->course_id]) }}" method="post">
                                @csrf
                                <input type="submit" value="снять" class="block text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-md text-sm px-5 py-0.5 text-center">
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <!-- Main modal -->
    <div id="assignTeacherModal" tabindex="-1" aria-hidden="true"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-4xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Выбор курсов
                    </h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                            data-modal-hide="assignTeacherModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="p-6">
                    <form action="{{ route('admin.assign.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="teacher"
                                   class="block mb-2 text-sm font-medium text-gray-900">Преподаватель:</label>
                            <select id="teacher" name="teacher"
                                    class="form-select w-full p-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Выберите преподавателя</option>
                                @foreach($teachers as $teacher)
                                    <option
                                        value="{{ $teacher->id }}">{{ $teacher->username }} {{ $teacher->surname }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="group" class="block mb-2 text-sm font-medium text-gray-900">Группа:</label>
                            <select id="group" name="group"
                                    class="form-select w-full p-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Выберите группу</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Курсы:</label>
                            <div id="courses" class="space-y-2">
                                <!-- Здесь динамически добавляются checkbox для курсов -->
                            </div>
                        </div>

                        <button id="submitBtn" type="submit"
                                class="w-full text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Добавить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script defer>
        document.addEventListener('DOMContentLoaded', function () {
            const teacherSelect = document.getElementById('teacher');
            const groupSelect = document.getElementById('group');
            const coursesContainer = document.getElementById('courses');

            function updateCourses() {
                const selectedTeacher = teacherSelect.value;
                const selectedGroup = groupSelect.value;

                coursesContainer.innerHTML = ''; // Очищаем контейнер с курсами

                if (selectedTeacher && selectedGroup) {
                    fetch(`/admin/show/teachers/get/${selectedGroup}/${selectedTeacher}`)
                        .then(response => response.json())
                        .then(data => {
                            const {courses, existingAssignments} = data;
                            courses.forEach(course => {
                                const label = document.createElement('label');
                                label.classList.add('inline-flex', 'items-center', 'mb-2', 'text-gray-900');

                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = 'courses[]';
                                checkbox.value = course.id;
                                checkbox.classList.add('mr-2', 'rounded', 'text-blue-600', 'focus:ring-blue-500', 'border-gray-300');

                                if (existingAssignments.includes(course.id)) {
                                    checkbox.checked = true;
                                }

                                label.appendChild(checkbox);
                                label.appendChild(document.createTextNode(course.title));
                                coursesContainer.appendChild(label);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            teacherSelect.addEventListener('change', updateCourses);
            groupSelect.addEventListener('change', updateCourses);
        });
    </script>

@endsection
