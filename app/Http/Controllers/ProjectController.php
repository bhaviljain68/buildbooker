<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        $projects = Project::where('organisation_id', auth()->user()->organisation_id)->get();
        return Inertia::render('projects/Index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('projects/Create');
    }

    /**
     * Display the specified resource.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255|string',
            'jurisdiction' => 'required|max:255|string',
            'office_display_address' => 'required|string',
            'site_display_address' => 'required|string',
            'total_units' => 'required|integer|gt:0',
            'rera_no' => 'required|max:255|string',
            'logo_file' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            $logoPath = processLogoImage($request->logo_file, $request->name, 'project_logos');
            $request->merge(['organisation_id' => auth()->user()->organisation_id, 'logo' => $logoPath]);
            $request->files->remove('logo_file');

            Project::create($request->except('logo_file'));

            return redirect()->route('projects.index')->with('success', 'Project Created successfully');
        } catch (Exception $e) {
            return redirect()->route('projects.index')->with('error', 'An error occurred while Creating the project. ');
        }
    }

    public function edit(Project $project)
    {
        return Inertia::render('projects/Edit', [
            'project' => $project,
        ]);;
    }

    public function update(Request $request, Project $project)
    {
      
        $request->validate([
            'name' => 'required|max:255|string',
            'jurisdiction' => 'required|max:255|string',
            'office_display_address' => 'required|string',
            'site_display_address' => 'required|string',
            'total_units' => 'required|integer|gt:0',
            'rera_no' => 'required|max:255|string',
            'logo_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        try {
            // 1) Get raw DB value (e.g., "storage/project_logos/xyz.webp")
            $rawLogo = $project->getRawOriginal('logo');
            $newLogo = $request->file('logo_file');
            if ($rawLogo && $newLogo) {
                // 2) Strip "storage/" and any leading slash
                $relativePath = ltrim(Str::replaceFirst('storage/', '', $rawLogo), '/');

                // 3) Optionally verify it exists
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }

                // 4) Process and store the new logo
                $logoPath = processLogoImage($request->logo_file, $request->name, 'project_logos');
                $request->merge([
                    'organisation_id' => auth()->user()->organisation_id,
                    'logo' => $logoPath,
                ]);
                $request->files->remove('logo_file');
            }
            $project->update($request->except('logo_file'));
            ToastMagic::success('Unit deleted successfully!');
            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (Exception $e) {
            return redirect()->route('projects.index')->with('error', 'An error occurred while updating the project. ');
        }
    }
}
