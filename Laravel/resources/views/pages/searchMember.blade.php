@extends('layouts.app')

@section('title', 'SearchMember')

@section('content')

<main>
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-12">
                <h4>
                    <i class="fas fa-search"></i>
                    Search Member
                </h4>
                <hr id="hr_space" class="mt-2">
            </div>
        </div>
        @php
            $i = 0;
        @endphp
        <div class="row">
            @forelse ($users as $user)
                @if ($i != 0 && !($i % 4))
                    </div>
                    <div class="row">
                @else
                    @php
                        $i++;
                    @endphp
                @endif
                <div class="col-lg-3 mb-3">
                    <a href="{{ route('profile', $user) }}" style="text-decoration: none;">
                        <div class="card" style="width: 17rem; height: 11rem;">
                            <div class="card-header">
                                <span>@</span>{{ $user->username }}
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $user->faculty->facultyname }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <h5 class="col-lg-12 text-center text-muted mt-5">
                    No results found.
                </h5>
            @endforelse
        </div>
    </div>
</main>

@endsection
