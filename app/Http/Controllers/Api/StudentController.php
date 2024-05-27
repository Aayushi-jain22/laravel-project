<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    public function index()
    {
        // $students = Student::all();

        $students= Cache::remember('students',15/60 ,function(){
           return Student::SimplePaginate(2);
        });
            
        
        if ($students->isEmpty()) {
            return response()->json(['message' => 'No records found'], 404);
        } else {
            return response()->json($students->items(), 200);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:40',
                'course' => 'required|string|max:40',
                'email' => 'required|email|max:40',
                'phone' => 'required|digits_between:1,15',
            ]);
            
            $student = new Student();
            $student->name = $request->name;
            $student->course = $request->course;
            $student->email = $request->email;
            $student->phone = $request->phone;
    
            if ($student->save()) {
                return response()->json(['message' => 'Student  added succesfully',], 200);  
            } else {
                return response()->json(['message' => 'Failed to add record'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
    

   public function show($id)
    {
        $student = Student::find($id);
        if ($student) {
            return response()->json(['message' => 'Student  found',], 404);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:40',
                'course' => 'required|string|max:40',
                'email' => 'required|email|max:40',
                'phone' => 'required|digits_between:1,15',
            ]);
    
            $student = Student::find($id);
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }
    
            $student->name = $request->name;
            $student->course = $request->course;
            $student->email = $request->email;
            $student->phone = $request->phone;
    
            if ($student->save()) {
                return response()->json(['message' => 'Record successfully updated'], 200);
            } else {
                return response()->json(['message' => 'Failed to update record'], 500);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
        if ($student->delete()) {
            return response()->json(['message' => 'Record successfully deleted'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete record'], 500);
        }
    }
   
}