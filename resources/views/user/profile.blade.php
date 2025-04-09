@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Profile</h1>
    @livewire('user-photo-upload')

    <h2>Your Photos</h2>
    <div class="photo-gallery">
        @foreach (auth()->user()->photos as $photo)
            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="User Photo" width="150">
        @endforeach
    </div>
</div>
@endsection