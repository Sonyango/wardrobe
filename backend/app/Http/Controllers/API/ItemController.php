<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Gender;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'gender' => 'required|string|in:men,women',
            'category' => 'required|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 1. Find the gender by name
        $gender = Gender::where('name', $data['gender'])->first();
        if (!$gender) {
            return response()->json(['error' => 'Gender not found'], 400);
        }

        //2. Find or create the category for that gender
        $category = Category::where('gender_id', $gender->id)
                            ->where('name', $data['category'])
                            ->first();

        if (!$category) {
            $category = Category::create([
                'gender_id' => $gender->id,
                'name'      => $data['category'],
                'type'      => $data['category'],
            ]);
        }
        // 3. Handle image upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $imageUrl = url('storage/' . $imagePath);
        }

        // 4. Create the item
        $item = Item::create([
            'user_id'       => auth()->id(),
            'category_id'   => $category->id,
            'gender_id'     => $gender->id,
            'name'          => $data['name'],
            'description'   => $data['description'] ?? null,
            'image_url'     => $imageUrl,
        ]);

        return response ()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'gender_id' => 'required|exists:genders,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image_url) {
                $oldPath = str_replace(url('storage/'), '', $item->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('items', 'public');
            $data['image_url'] = url('storage/' . $imagePath);
        }

        $item->update($data);
        return $item;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
