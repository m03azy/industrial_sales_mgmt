<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $factory = $user->factory;

        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'Factory profile not found.');
        }

        return view('factory.profile.edit', compact('factory'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $factory = $user->factory;

        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'Factory profile not found.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'description' => 'nullable|string',
            'operating_hours' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('factory-logos', 'public');
            $validated['logo'] = $path;
        }

        $factory->update($validated);

        return redirect()->route('factory.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
