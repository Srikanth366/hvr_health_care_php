<?php

namespace App\Http\Controllers;


use App\Models\Version;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    // Insert Version Data
    public function versionSave(Request $request)
    {
        $validatedData = $request->validate([
            'latestVersion' => 'required|string',
            'description' => 'nullable|string',
            'updateUrl' => 'required|string',
            'type' => 'required|string',
            'mandatory' => 'required|string',
        ]);

        $version = Version::create($validatedData);

        if ($version) {
            return response()->json([
                'status' => true,
                'message' => 'Version data stored successfully.',
                'data' => $version,
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to store version data.',
            ], 500);
        }
    }


    public function index()
    {
        $versions = Version::all();

        return response()->json([
            'status' => 'success',
            'data' => $versions,
        ]);
    }

    public function versionCheck(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string',
            'version' => 'required|string',
        ]);

        $version = Version::where('type', $validatedData['type'])
                           ->where('latestVersion', $validatedData['version'])
                           ->first();

        if ($version) {
            return response()->json([
                'status' => true,
                'message' => 'Version data found.',
                'data' => $version,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Version data not found.',
            ], 404);
        }
    }
}
