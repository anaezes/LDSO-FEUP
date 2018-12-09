@extends('layouts.app')

@section('title', 'Create proposal')

@section('content')
<!-- Content create proposal -->
<div class="container mt-5 mb-5">
    <main>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="d-flex align-items-center">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
            {{ csrf_field() }}
            <div class="form-row">
                <div class="form-group col-lg-12">
                    <h4>
                        <label for="title">Title</label>
                    </h4>
                    <input type="text" name="title" id="title" class="form-control" minlength="8" maxlenght="80" placeholder="Proposal's title" value="{{ old('title') }}" required>
                </div>
            </div>

            <hr id="hr_space" class="mt-2">

            <div class="form-row">
                <div class="form-group col-lg-12">
                    <h4>
                        <label for="description">Description</label>
                    </h4>
                    <textarea name="description" id="description" class="form-control" cols="80" rows="8" minlength="20" placeholder="Proposal's description" required>{{ old('description') }}</textarea>
                </div>
            </div>

            <hr id="hr_space" class="mt-2">

            <div class="form-row">
                <div class="form-group col-lg-12">
                    <h4>
                        <label for="skill">Skills</label>
                    </h4>
                    <select name="skill[]" id="skill" class="form-control" size="8"  multiple>
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
                        <label for="faculty">Faculties</label>
                    </h4>
                    <select name="faculty[]" id="faculty" class="form-control" size="8" multiple>
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
                            <input type="number" id="days" name="days" class="form-control" min="0" max="13" value="0" placeholder="Days" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <h5>
                                <label for="hours">Hours</label>
                            </h5>
                            <input type="number" id="hours" name="hours" class="form-control" min="0" max="23" value="0" placeholder="Hours" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <h5>
                                <label for="minutes">Minutes</label>
                            </h5>
                            <input type="number" id="minutes" name="minutes" class="form-control" min="0" max="59" value="0" placeholder="Minutes" required>
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
                                <label for="announce">Winner annoucement date</label>
                            </h5>
                            <small>
                                Date by which a winner must be selected.
                            </small>
                            <input type="date" name="announce" class="form-control mt-3" min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+3 month')) }}" required>
                        </div>
                        <div class="col-lg-2"></div>
                        <div class="form-group col-lg-5">
                            <h5>
                                <label for="due">Proposal due date</label>
                            </h5>
                            <small>
                                Date by which a team must submit its project.
                            </small>
                            <input type="date" name="due" class="form-control mt-3" min="{{ date('Y-m-d', strtotime('+1 day')) }}" max="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
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
                            <div class="form-check">
                                <h5>
                                    <input type="checkbox" name="public_prop" class="form-check-input">
                                    <label for="public_prop" class="form-check-label">Public proposal</label>
                                </h5>
                            </div>
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

            <hr id="hr_space" class="mt-2">

            <div class="form-row">
                <div class="form-group col-lg-12 d-flex justify-content-end">
                    <input type="submit" value="Create" class="btn btn-outline-primary mr-2 w-25">
                    <input type="reset" value="Clear" class="btn btn-outline-secondary mr-2">
                    <script>
                        document.write('<a href="'+document.referrer+'" class="btn btn-outline-danger">Back</a>')
                    </script>
                </div>
            </div>
        </form>
    </main>
</div>
@endsection
