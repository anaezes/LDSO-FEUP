@extends('layouts.app')

@section('title', 'SearchMember')

@section('content')

<main>
    <div class="container mt-5 mb-5">
        <div class="row d-flex align-items-center">
            <div class="col-lg-10">
                <h4>
                    <i class="fas fa-search"></i>
                    Search Member
                </h4>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-primary w-100 mb-2" type="button" data-toggle="collapse" data-target="#collapseSearchMember" aria-expanded="false" aria-controls="collapseSearchMember">
                    Search
                </button>
            </div>
        </div>
        <hr id="hr_space" class="mt-2">
        <div class="row mb-4">
            <div class="col-lg-12 collapse" id="collapseSearchMember">
                <div class="card card-body">
                    <form action="{{ route('searchMember') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-row">
                            <div class="form-group col-lg-12">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="User's name or username">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-12">
                                <label for="skills">Skill</label>
                                <select name="skills[]" id="skills" class="form-control" size="6" multiple>
                                @foreach (App\Skill::all() as $skill)
                                    <option value="{{ $skill->id }}">{{ $skill->skillname }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-12">
                                <label for="faculties">Faculty</label>
                                <select name="faculties[]" id="faculties" class="form-control" size="6" multiple>
                                @foreach (App\Faculty::all() as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->facultyname }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-row float-right mb-3">
                            <div class="col-lg-12">
                                <button type="submit" name="Search" class="btn btn-primary">
                                <span class="fas fa-search"></span>
                                Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
