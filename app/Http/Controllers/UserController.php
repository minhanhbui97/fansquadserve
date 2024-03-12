<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseId = request()->query('course_id');
        $course = Course::find($courseId);
        if ($course) { // Get list of tutors available for a course
            $tutors = $course->users;
        } else {
            $tutors = User::all();
        }
        return $tutors;
    }

}
