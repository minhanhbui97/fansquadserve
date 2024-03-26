<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Course;
use App\Models\SchedulePage;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     
     */

     

    public function index()

    {

        $users = User::with(['roles'])->get();
         return $users;

        $course_id = request()->query('course_id');
        $course = Course::find($course_id);

        if ($course) { // Get list of tutors available for a course
            $tutors = $course->users()->with(['schedule_page'])->get();
        } else {
            $tutors = User::all();
        }
        return $tutors;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $body = $request->all();
        $body_user = $request->except(['role_ids', 'course_ids', 'schedule_page']);

        $user = User::create($body_user);

        $roles = $body['roles'];
        $role_ids = array_map(fn ($value): int => $value['id'], $roles);

        // Attach many-to-many relationshup with role
        $user->roles()->attach($role_ids);

        // If roles_ids contain 1 (role tutor)
        if (array_search(1, array_column($roles, 'id')) !== false) {
            //Attach many-to-many relationship with course 
            $courses = $body['courses'];
            $courses_ids = array_map(fn ($value): int => $value['id'], $courses);
            $user->courses()->attach($courses_ids);

            // Add one-to-one relationship with schedule_page 
            $schedule_page = new SchedulePage();
            $schedule_page->schedule_url = $body['schedule_page'];
            $user->schedule_page()->save($schedule_page);
        }

        $user->refresh();
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->is_active = $request->get('is_active');
        $user->save();
        return $user;
    }

    
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->with(['roles','courses','schedule_pages'])->first();
        return $user;
    }

}
