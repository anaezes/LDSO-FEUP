@extends('layouts.app')

@section('title', 'Search')

@section('content')
<main>
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-lg-12">
                <h4>
                    <i class="fas fa-search"></i>
                    Search
                </h4>
                <hr id="hr_space" class="mt-2">
            </div>
        </div>
        <ul class="nav nav-pills mb-3 row" id="pills-tab" role="tablist">
            <li class="nav-item col-lg-4 text-center">
                <a class="nav-link active" id="pills-users-tab" data-toggle="pill" href="#pills-users" role="tab" aria-controls="pills-users" aria-selected="true">Users</a>
            </li>
            <li class="nav-item col-lg-4 text-center">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Proposals</a>
            </li>
            <li class="nav-item col-lg-4 text-center">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Teams</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            @php
                $i = 0;
            @endphp
            <div class="tab-pane fade show active" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
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
                        <h5 class="text-center text-muted mt-5">
                            No results found.
                        </h5>
                    @endforelse
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
        </div>
    </div>
</main>
@endsection
