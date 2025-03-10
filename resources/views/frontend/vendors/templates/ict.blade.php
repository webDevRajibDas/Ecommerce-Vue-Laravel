@extends('layouts.app')

@section('content')
    <h1>{{ $category->name }}</h1>
    <p>This is the ICT vendors page.</p>

    <ul>
        @foreach($vendors as $vendor)
            <li>{{ $vendor->name }}</li>
        @endforeach
    </ul>
@endsection