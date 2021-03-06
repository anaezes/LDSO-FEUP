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
                <a class="nav-link" id="pills-proposals-tab" data-toggle="pill" href="#pills-proposals" role="tab" aria-controls="pills-proposals" aria-selected="false">Proposals</a>
            </li>
            <li class="nav-item col-lg-4 text-center">
                <a class="nav-link" id="pills-teams-tab" data-toggle="pill" href="#pills-teams" role="tab" aria-controls="pills-teams" aria-selected="false">Teams</a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
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
            <div class="tab-pane fade" id="pills-proposals" role="tabpanel" aria-labelledby="pills-proposals-tab">
                @php
                    $i = 0;
                @endphp
                <div class="row">
                    @forelse ($proposals as $proposal)
                        @if ($i != 0 && !($i % 4))
                            </div>
                            <div class="row">
                        @else
                            @php
                                $i++;
                            @endphp
                        @endif
                        <div class="col-lg-3 mb-3">
                            <a href="{{ route('proposal', $proposal) }}" style="text-decoration: none;">
                                <div class="card" style="width: 17rem;">
                                    <div class="card-header">
                                        {{ $proposal->title }}
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-dark">
                                            {{ substr($proposal->description, 0, 100) }}
                                        </h5>
                                        <h6 class="card-subtitle mt-3 text-muted">
                                            {{ $proposal->user->name }}
                                        </h6>
                                        <h6 class="text-danger mt-3">
                                            {{ $proposal->timestamp }}
                                        </h6>
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
            <div class="tab-pane fade" id="pills-teams" role="tabpanel" aria-labelledby="pills-teams-tab">
                @php
                    $i = 0;
                @endphp
                <div class="row">
                    @forelse ($teams as $team)
                        @if ($i != 0 && !($i % 4))
                            </div>
                            <div class="row">
                        @else
                            @php
                                $i++;
                            @endphp
                        @endif
                        <div class="col-lg-3 mb-3">
                            <a href="{{ route('team.show', $team) }}" style="text-decoration: none;">
                                <div class="card" style="width: 17rem;">
                                    <div class="card-header">
                                        {{ $team->teamname }}
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title text-dark">
                                            {{ substr($team->teamdescription, 0, 100) }}
                                        </h5>
                                        <h6 class="card-subtitle mt-3 text-muted">
                                            {{ $team->user->name }}
                                        </h6>
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
        </div>
    </div>
</main>
@endsection
