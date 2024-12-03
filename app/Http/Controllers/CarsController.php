<?php

namespace App\Http\Controllers;

use App\Models\cars;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return cars::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255', // Required, string, max length 255
            'type' => 'required|string|max:100', // Required, string, max length 100
            'price' => 'required|numeric|min:0', // Required, numeric, must be >= 0
            'color' => 'required|string|max:50', // Required, string, max length 50
            'description' => 'nullable|string|max:500', // Optional, string, max length 500
            'number_of_cars' => 'required|integer|min:1', // Required, integer, must be >= 0
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional, image, specific formats, max 2MB
        ]);

        // if ($request->hasFile('picture')) {
        //     $fields['picture'] = $request->file('picture')->store('pictures', 'public');
        // }



        $car = Cars::create($fields);

        // Return the created car as a response
        return response()->json(['car' => $car], 200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the car by its ID
        $car = Cars::find($id);

        // Check if the car exists
        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        // Return the car details as a JSON response
        return response()->json(['car' => $car], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $fields = $request->validate([
            'name' => 'required|string|max:255', // Required, string, max length 255
            'type' => 'required|string|max:100', // Required, string, max length 100
            'price' => 'required|numeric|min:0', // Required, numeric, must be >= 0
            'color' => 'required|string|max:50', // Required, string, max length 50
            'description' => 'nullable|string|max:500', // Optional, string, max length 500
            'number_of_cars' => 'required|integer|min:1', // Required, integer, must be >= 1
            'picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Optional, image, specific formats, max 2MB
        ]);

        // Find the car by its ID
        $car = Cars::find($id);

        // Check if the car exists
        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        // Handle picture upload if provided
        if ($request->hasFile('picture')) {
            // Delete old picture if it exists
            if ($car->picture) {
                unlink(storage_path('app/public/' . $car->picture));
            }
            // Store new picture
            $fields['picture'] = $request->file('picture')->store('pictures', 'public');
        }

        // Update the car record with validated fields
        $car->update($fields);

        // Return the updated car as a response
        return response()->json(['car' => $car], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cars $car)
    {
        // Check if the car exists
        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }

        // Handle the picture deletion if it exists
        

        // Delete the car record from the database
        $car->delete();

        // Return a success message
        return response()->json(['message' => 'Car deleted successfully'], 200);
    }
}
