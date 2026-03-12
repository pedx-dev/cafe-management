<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class HomeController extends Controller
{
    public function index()
    {
        $featuredItems = MenuItem::where('is_featured', true)
                                ->where('is_available', true)
                                ->take(6)
                                ->get();
        
        $categories = MenuItem::select('category')
                            ->distinct()
                            ->pluck('category');
        
        return view('home', compact('featuredItems', 'categories'));
    }
}