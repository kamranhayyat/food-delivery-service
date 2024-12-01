<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function fileUpload(Request $request) {
        $request->validate([
            'file' => 'required|file|max:2048',
            'fileable_id' => 'required|integer',
            'fileable_type' => 'required|string',
            'file_type' => 'nullable|string'
        ]);

        $file = $request->file('file');
        $filePath = $file->store('uploads', 'public');

        $uploadedFile = File::query()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $request->input('file_type'),
            'fileable_id' => $request->input('fileable_id'),
            'fileable_type' => $request->input('fileable_type'),
        ]);

        return response()->json([
            'file' => $uploadedFile,
            'message' => 'File uploaded successfully!'
        ]);
    }
}
