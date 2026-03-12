<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::where('is_available', true);
        
        // Filter by category - check for non-empty category
        $category = $request->get('category');
        if (!empty($category) && $category != 'all') {
            $query->where('category', $category);
        }
        
        // Search - use closure to properly scope the OR conditions
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        // Sort
        $sortParam = $request->get('sort', 'name');
        
        switch ($sortParam) {
            case 'price':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', 'asc');
                break;
        }
        
        $menuItems = $query->paginate(12);
        $categories = MenuItem::select('category')->distinct()->pluck('category');
        
        return view('menu.index', compact('menuItems', 'categories'));
    }
    
    public function show($id)
    {
        $item = MenuItem::findOrFail($id);
        $relatedItems = MenuItem::where('category', $item->category)
                               ->where('id', '!=', $item->id)
                               ->where('is_available', true)
                               ->where('stock', '>', 0)
                               ->take(4)
                               ->get();
        
        return view('menu.show', compact('item', 'relatedItems'));
    }
}