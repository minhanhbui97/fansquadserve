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

        $course_id = request()->query('course_id');
        $course = Course::find($course_id);

        if ($course) { // Get list of tutors available for a course
            $tutors = $course->users()->with(['schedule_page', 'roles'])->get();
            return $tutors;
        }

        return $users;
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
        $body = $request;
        $user->first_name = $body->get('first_name');
        $user->last_name = $body->get('last_name');
        $user->email = $body->get('email');

        if ($body->get('password')) {
            $user->password = $body->get('password');
        }

        $role_ids = $body->get('roles');
        $user->roles()->sync($role_ids);

        if (in_array(1, $role_ids)) {
            $course_ids = $body->get('courses');
            $user->courses()->sync($course_ids);

            if ($user->schedule_page === null) {
                $schedule_page = new SchedulePage();
                $schedule_page->schedule_url = $body->get('schedule_page');
                $user->schedule_page()->save($schedule_page);
            } else {
                $user->schedule_page->update([
                    'schedule_url' => $body->get('schedule_page')
                ]);
            }
        } else {
            $user->courses()->detach();
            $user->schedule_page()->delete();
        }

        $user->is_active = $body->get('is_active');

        $user->save();
        $user->refresh();
        $user = User::where('id', $user->id)->with(['roles', 'courses', 'schedule_page'])->first();
        return $user;
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::where('id', $id)->with(['roles', 'courses', 'schedule_page'])->first();
        return $user;
    }
}
