<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'canRegister' => Features::enabled(Features::registration()),
            'featuredProducts' => \App\Models\Product::all(),
            'slides' => [
                [
                    'image' => '/images/slider-1.jpg',
                    'title' => 'Summer Collection 2023',
                    'description' => 'Discover our latest summer dresses and accessories'
                ],
            ]
        ]);
    }
}
