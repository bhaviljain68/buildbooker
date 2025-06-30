<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganisationController extends Controller
{
    public function edit()
    {
        return Inertia::render('organisation/Edit', ['organisation' => Organisation::find(auth()->user()->organisation_id)]);
    }


    public function update(Request $request)
    {
     
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email',
            'mobile' => 'required|regex:/^[0-9]{10}$/',
            'logo' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $organisation = Organisation::find(auth()->user()->organisation_id);
        // dd($organisation);
        try {
            $logoPath = processLogoImage($validatedData['logo'], $request->name, 'organisation_logos');
            $organisation->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'mobile' => $validatedData['mobile'],
                'logo' => $logoPath,
            ]);

            ToastMagic::success("Organization updated successfully!");
            return redirect()->back()->with('success', 'Organisation updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred during the update of the organisation details.');
        }
    }
}
