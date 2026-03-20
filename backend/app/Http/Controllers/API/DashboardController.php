<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Gender;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        // Get gender IDs
        $menGender = Gender::where('name', 'men')->first();
        $womenGender = Gender::where('name', 'women')->first();

        // Count Total clothes
        $totalClothes = Item::whereHas('category', function($query) {
            $query->where('type', 'clothes');
        })->count();

        // Clothes by gender
        $clothesMen = Item::whereHas('category', function($query) {
            $query->where('type', 'clothes');
        })->where('gender_id', $menGender->id)->count();

        $clothesWomen = Item::whereHas('category', function($query) {
            $query->where('type', 'clothes');
        })->where('gender_id', $womenGender->id)->count();

        // Count Total shoes
        $totalShoes = Item::whereHas('category', function($query) {
            $query->where('type', 'shoes');
        })->count();

        // Shoes by gender
        $shoesMen = Item::whereHas('category', function($query) {
            $query->where('type', 'shoes');
        })->where('gender_id', $menGender->id)->count();

        $shoesWomen = Item::whereHas('category', function($query) {
            $query->where('type', 'shoes');
        })->where('gender_id', $womenGender->id)->count();

        return response()->json([
            'clothes' => [
                'total' => $totalClothes,
                'men' => $clothesMen,
                'women' => $clothesWomen
            ],
            'shoes' => [
                'total' => $totalShoes,
                'men' => $shoesMen,
                'women' => $shoesWomen
            ]
        ]);
    }

    public function categoryStats(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:men,women',
            'type' => 'required|in:clothes,shoes'
        ]);

        // Get gender ID
        $gender = Gender::where('name', $request->gender)->first();

        if (!$gender) {
            return response()->json(['message' => 'Gender not found.'], 404);
        }

        // Get grouped items
        $items = Item::where('gender_id', $gender->id)
            ->whereHas('category', function($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->selectRaw('name, COUNT(*) as total')
            ->groupBy('name')
            ->orderByDesc('total')
            ->get();

        return response()->json($items);
    }
}
