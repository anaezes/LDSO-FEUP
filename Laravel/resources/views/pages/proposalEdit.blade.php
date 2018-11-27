@extends('layouts.app')

@section('title', $proposal->title)

@section('content')

<main>
    <div class="container mb-5 mt-5">
        <div class="row">
            <h1>Edit Proposal {{ $proposal->title }}</h1>
        </div>
    </div>
</main>

@endsection