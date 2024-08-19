<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    // Display a single course
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    // Handle enrollment
    public function enroll(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();

        // Check if the user is already enrolled
        if (Enrollment::where('course_id', $course->id)->where('student_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // Enroll the user in the course
        Enrollment::create([
            'course_id' => $course->id,
            'student_id' => $user->id,
        ]);

        return redirect()->back()->with('success', 'You have successfully enrolled in the course.');
    }
}
