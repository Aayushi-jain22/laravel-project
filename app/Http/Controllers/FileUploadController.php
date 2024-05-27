<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use App\Models\File;
 use Illuminate\Support\Facades\Storage; // Make sure to import this

class FileUploadController extends Controller
{

    public function FileUpload(Request $request)
        {
            // \Log::info('Request data:', $request->all());
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'details' => 'nullable|string',
                'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:10240', // Accept only image files with specific mime types
            ]);

            // Store the uploaded file
            $uploaded_files = $request->file('file')->store('public/uploads');

        
            $filedb = new File;
            $filedb->title = $request->title;
            $filedb->details = $request->details;
            $filedb->image = $request->file('file')->hashName();
            $filedb->save();

        
            if ($filedb) {
                return response()->json([
                    "result" => $uploaded_files,
                    "message" => "File uploaded successfully"
                ], 201);
            } else {
                return response()->json([
                    "message" => "File not uploaded"
                ], 500);
            }
        }

        public function index()
        {
            $files = File::all();
            return response()->json($files, 200);
        }
    

    public function show($id)
    {
        $file = File::find($id);
        if ($file) {
            return response()->json($file, 200);
        } else {
            return response()->json(["message" => "File not found"], 404);
        }
    }

    public function update(Request $request, $id)
    {
        \Log::info('Update request payload:', $request->all()); 
    
        // Find the file record by ID
        $file = File::find($id);
    
        if ($file) {
            // Validate the request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'details' => 'nullable|string',
                'file' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240', // File is optional in update
            ]);
    
            \Log::info('Update request validated:', $validated);
    
            // Check if a new file has been uploaded
            if ($request->hasFile('file')) {
                // Delete the old file from storage
                Storage::delete('public/uploads/' . $file->image);
    
                // Store the new file
                $uploaded_file = $request->file('file')->store('public/uploads');
                $file->image = $request->file('file')->hashName();
    
                \Log::info('New file uploaded:', ['file' => $file->image]);
            }
    
            // Update the file details
            $file->title = $request->title;
            $file->details = $request->details;
            $file->save();
    
            \Log::info('File updated successfully:', ['id' => $file->id]);
    
            // Return a successful response
            return response()->json(["message" => "File updated successfully"], 200);
        } else {
            \Log::error('File not found:', ['id' => $id]);
            return response()->json(["message" => "File not found"], 404);
        }
    }
    
    public function destroy($id)
    {
        $file = File::find($id);
        if ($file) {
           
            Storage::delete('public/uploads/' . $file->image);
            $file->delete();

            return response()->json(["message" => "File deleted successfully"], 200);
        } else {
            return response()->json(["message" => "File not found"], 404);
        }
    }
}
