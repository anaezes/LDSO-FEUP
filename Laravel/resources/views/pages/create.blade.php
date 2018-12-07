@extends('layouts.app')

@section('title', 'Create proposal')

@section('content')
<!-- Content create proposal -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-12">
            <h4>
                <i class="fa fa-plus"></i>
                Create a proposal
            </h4>
        </div>
    </div>

    <hr id="hr_space" class="mt-2">

    <form action="{{ route('create') }}" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-lg-12">
                <h4>
                    <label for="title">Title</label>
                </h4>
                <input type="text" name="title" class="form-control" minlength="10" maxlenght="80" placeholder="Proposal's title" required>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="form-row">
            <div class="form-group col-lg-12">
                <h4>
                    <label for="description">Description</label>
                </h4>
                <textarea name="description" class="form-control" cols="80" rows="5" minlength="20" placeholder="Proposal's description" required></textarea>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="form-row">
            <div class="form-group col-lg-12">
                <h4>
                    <label for="skills">Skills</label>
                </h4>
                <select name="skills" class="form-control" size="8"  multiple>
                    @foreach(App\Skill::orderBy('skillname')->get() as $skill)
                        <option value="{{ $skill->id }}" class="p-1">{{ $skill->skillname }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="form-row">
            <div class="form-group col-lg-12">
                <h4>
                    <label for="faculties">Faculties</label>
                </h4>
                <select name="faculties" class="form-control" size="8" multiple>
                    @foreach(App\Faculty::orderBy('facultyname')->get() as $faculty)
                        <option value="{{ $faculty->id }}" class="p-1">{{ $faculty->facultyname }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="form-row">
            <div class="form-group col-lg-6">
                <h4>
                    <label>Duration</label>
                </h4>
                <small style="font-size: small">
                    maximum of 13 days, 23 hours, and 59 minutes.
                </small>
                <div class="form-row mt-3">
                    <div class="form-group col-lg-4">
                        <h5>
                            <label for="days">Days</label>
                        </h5>
                        <input type="number" name="days" class="form-control" min="0" max="13" value="0">
                    </div>
                    <div class="form-group col-lg-4">
                        <h5>
                            <label for="hours">Hours</label>
                        </h5>
                        <input type="number" name="hours" class="form-control" min="0" max="23" value="0">
                    </div>
                    <div class="form-group col-lg-4">
                        <h5>
                            <label for="minutes">Minutes</label>
                        </h5>
                        <input type="number" name="minutes" class="form-control" min="0" max="59" value="0">
                    </div>
                </div>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="form-row">
            <div class="form-group col-lg-8">
                <h4>
                    <label>Dates</label>
                </h4>
                <div class="form-row mt-3">
                    <div class="form-group col-lg-5">
                        <h5>
                            <label for="annoucement">Winner annoucement date</label>
                        </h5>
                        <small>
                            Date by which a winner must be selected.
                        </small>
                        <input type="date" name="annoucement" class="form-control mt-3">
                    </div>
                    <div class="col-lg-2"></div>
                    <div class="form-group col-lg-5">
                        <h5>
                            <label for="due">Proposal due date</label>
                        </h5>
                        <small>
                            Date by which a team must submit its project.
                        </small>
                        <input type="date" name="due" class="form-control mt-3">
                    </div>
                </div>
            </div>
        </div>

        <hr id="hr_space" class="mt-2">

        <div class="form-row">
            <div class="form-group col-lg-8">
                <h4>
                    <label>Privacy</label>
                </h4>
                <div class="form-row mt-3">
                    <div class="form-group col-lg-8">
                        <h5>
                            <input type="checkbox" name="public_prop"> Public proposal<br>
                        </h5>
                        <small>
                            The proposal will be visible for every user.
                        </small>
                    </div>
                </div>
                <div class="form-row mt-3">
                    <div class="form-group col-lg-8">
                        <div class="form-check">
                            <h5>
                                <input type="checkbox" name="public_bid" class="form-check-input">
                                <label for="public_bid" class="form-check-label">Public bid</label>
                            </h5>
                        </div>
                        <small>
                            The list of bids in the proposal page will be visible for every user.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>




    <div class="container-fluid bg-white">
    <div class="bg-white mb-0 mt-4 pt-4 panel">
        <h4>
            <i class="fa fa-plus"></i>
            Create a Proposal
        </h4>
    </div>
    <hr id="hr_space" class="mt-2">
    <main>
        <form class="ml-4 mr-4" method="POST" action="{{ route('create') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="form-group col-md-12">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required>
                @if ($errors->has('title'))
                  <span class="error">
                    {{ $errors->first('title') }}
                  </span>
                @endif
            </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6"> <!-- todo: get database skills -->
                    <label for="skill">Skill</label>
                    <select id="skill" name="skill[]" class="form-control" value="{{ old('skill') }}" multiple required>
                        <option>Skill1</option>
                        <option>Skill2</option>
                        <option>Skill3</option>
                        <option>Skill4</option>
                    </select>
                    @if ($errors->has('skill'))
                    <span class="error">
                        {{ $errors->first('skill') }}
                      </span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="faculty">Faculty</label>
                    <select id="faculty" name="faculty[]" class="form-control" value="{{ old('faculty') }}" multiple required>
                        <option>Faculty of Architecture</option>
                        <option>Faculty of Fine Arts</option>
                        <option>Faculty of Science</option>
                        <option>Faculty of Sport</option>
                        <option>Faculty of Law</option>
                        <option>Faculty of Economics</option>
                        <option>Faculty of Engineering</option>
                        <option>Faculty of Pharmacy</option>
                        <option>Faculty of Arts</option>
                        <option>Faculty of Medicine</option>
                        <option>Faculty of Dental Medicine</option>
                        <option>Faculty of Psychology and Education Science</option>
                        <option>Abel Salazar Institute of Biomedical Science</option>
                        <option>Porto Business School</option>
                    </select>
                    @if ($errors->has('faculty'))
                      <span class="error">
                        {{ $errors->first('faculty') }}
                      </span>
                    @endif
                </div>
            </div>


            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" cols="30" class="form-control" placeholder="Describe this proposal: things you may consider important." value="{{ old('description') }}" required></textarea>
                    @if ($errors->has('description'))
                      <span class="error">
                        {{ $errors->first('description') }}
                      </span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><i class="fa fa-clock"></i> Duration</label>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="days">Days</label>
                            <select id="days" name="days" class="form-control" required>
                                <option value="0">&nbsp;</option>
                                @for($i = 0; $i < 14; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            @if ($errors->has('images'))
                            <span class="error">
                            {{ $errors->first('images') }}
                          </span>
                            @endif
                        </div>
                        <div class="form-group col-md-2">
                            <label for="hours">Hours</label>
                            <select id="hours" name="hours" class="form-control" required>
                                <option value="0">&nbsp;</option>
                                @for($i = 0; $i < 24; $i++)
                                <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            @if ($errors->has('hours'))
                            <span class="error">
                            {{ $errors->first('hours') }}
                          </span>
                            @endif
                        </div>
                        <div class="form-group col-md-3">
                            <label for="minutes">Minutes</label>
                            <input id="minutes" class="form-control" type="number" name="minutes" min="0" max="59" required value="0">
                            @if ($errors->has('minutes'))
                            <span class="error">
                            {{ $errors->first('minutes') }}
                          </span>
                            @endif
                        </div>
                    </div>
                </div>


            <div class="form-group col-md-6"> <!-- todo triggers to database-->
                <label><i class="fa fa-calendar"></i> Dates</label>
                <div class="form-row">
                <div class="form-group col-md-4">
                <label for="start">Announce winner</label>
                <input type="date" name="announce" required>
                </div>
                <div class="form-group col-md-4">
                <label>Due of project  </label>
                <input type="date" name="due" required>
                </div>
                </div>
            </div>

            </div>

            <div class="form-row">
            <div class="form-group col-md-6">
                    <label><i class="fa fa-stop-circle"></i> Privacy</label>
                    <div class="form-row">
                        <div class="checkbox col-md-6">
                            <label for="agree">
                                <input id="public_prop[]" name="public_prop" type="checkbox">
                                Public Proposal</label>
                        </div>
                        <div class="checkbox col-md-6">
                            <label for="agree">
                                <input id="public_bid[]" name="public_bid" type="checkbox">
                                Public Bid</label>
                        </div>
                    </div>
                </div>
            </div>
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary col-md-12">Create proposal</button>
                </div>

        </form>

    </main>
</div>
</div>
@endsection
