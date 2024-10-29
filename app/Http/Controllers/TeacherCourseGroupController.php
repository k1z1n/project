<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherCourseGroupController extends Controller
{
    public function remove($teacher_id, $group_id, $course_id)
    {
        // Удаляем назначение преподавателя с курса и группы
        DB::table('teacher_course_groups')
            ->where('teacher_id', $teacher_id)
            ->where('group_id', $group_id)
            ->where('course_id', $course_id)
            ->delete();

        // Вернуть назад с сообщением об успехе
        return redirect()->back()->with('success', 'Преподаватель успешно снят с курса.');
    }

}
