@extends('layouts.app')

@section('title', $proposal->title)

@section('content')

<main>
    <div class="container mb-5 mt-5">
        <div class="row">
            <div class="col-lg-12">
                <h1>Edit Proposal</h1>
            </div>
        </div>

        <div style="border: 1px solid gray" class="mt-3"></div>
        
        <form action="{{ route('proposal.update', $proposal->id) }}" class="form-group" method="POST">
            {!! method_field('PUT') !!}
            {{ csrf_field() }}
            <div class="row mt-3 d-flex align-items-center">
                <div class="col-lg-4">
                    <h3>
                        <label for="proposalTitle">
                            <strong>
                                Proposal Title
                            </strong>
                        </label>
                    </h3>
                </div>
                <div class="col-lg-8">
                    <input type="text" class="w-100" name="proposalTitle" id="proposalTitle" required minlength="8" maxlength="80" placeholder="Proposal's title" value="{{ $proposal->title }}">
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row mt-3 d-flex align-items-center">
                <div class="col-lg-4">
                    <h3>
                        <label for="proposalDescription">
                            <strong>
                                Proposal Description
                            </strong>
                        </label>
                    </h3>
                </div>
                <div class="col-lg-8">
                    <textarea name="proposalDescription" cols="70" rows="5" class="w-100" minlength="20" placeholder="Proposal's description" required>{{ $proposal->description }}</textarea>
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row mt-3 d-flex align-items-center">
                <div class="col-lg-4">
                    <h3>
                        <label for="proposalSkills">
                            <strong>
                                Proposal Skills
                            </strong>
                        </label>
                    </h3>
                </div>
                <div class="col-lg-8">
                    <select class="form-control" name="proposalSkills[]" multiple>
                        @foreach(App\Skill::get() as $skill)
                          <option value="{{ $skill->id }}" @foreach($proposal->skill as $ps) @if($skill->id == $ps->id)selected="selected"@endif @endforeach>{{ $skill->skillname }}</option>
                        @endforeach
                      </select>
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row mt-3 d-flex align-items-center">
                <div class="col-lg-4">
                    <h3>
                        <label for="proposalFaculty">
                            <strong>
                                Proposal Faculties
                            </strong>
                        </label>
                    </h3>
                </div>
                <div class="col-lg-8">
                    <select class="form-control" name="proposalFaculty[]" multiple>
                        @foreach(App\Faculty::get() as $faculty)
                          <option value="{{ $faculty->id }}" @foreach($proposal->faculty as $pf) @if($faculty->id == $pf->id)selected="selected"@endif @endforeach>{{ $faculty->facultyname }}</option>
                        @endforeach
                      </select>
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row mt-3 d-flex align-items-center">
                <div class="col-lg-4">
                    <h3>
                        <label for="proposalDueDate">
                            <strong>
                                Due Date
                            </strong>
                        </label>
                    </h3>
                    <h6 class="text-muted" style="font-size: small">
                        Date when the winner team must submit its project
                    </h6>
                </div>
                <div class="col-lg-8">
                    <?php
                        $created = strtotime($proposal->datecreated);
                        $duedate = strtotime($proposal->duedate);
                        $announce = strtotime($proposal->announcedate);
                        $day = 86400;
                        $month = $day * 30;
                        $year = $day * 365;
                        $today = date('Y-m-d');
                    ?>
                    <input type="date" name="proposalDueDate" value="{{ date('Y-m-d', $duedate) }}" min="{{ date('Y-m-d', $created + $proposal->duration) }}" max="{{ date('Y-m-d',$created + 1*$year) }}" required>
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row mt-3 d-flex align-items-center">
                <div class="col-lg-4">
                    <h3>
                        <label for="proposalAnnounceDate">
                            <strong>
                                Winner Announcement Date
                            </strong>
                        </label>
                    </h3>
                    <h6 class="text-muted" style="font-size: small">
                        Date when a winner team must be selected
                    </h6>
                </div>
                <div class="col-lg-8">
                    <input type="date" name="proposalAnnounceDate" value="{{ date('Y-m-d', $announce) }}" min="{{ date('Y-m-d', $created + $proposal->duration) }}" max="{{ date('Y-m-d', $created + $proposal->duration + 3*$month) }}" required>
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row mt-3 d-flex align-items-center form-check pl-0">
                <div class="col-lg-6">
                    <h3>
                        <label for="proposalPublic">
                            <strong>
                                Proposal Public
                            </strong>
                        </label>
                        <input type="checkbox" name="proposalPublic" @if($proposal->proposal_public) checked @endif>
                    </h3>
                    <h6 class="text-muted"  style="font-size: small">
                        If checked, the proposal will be available for every user
                    </h6>
                </div>
            </div>

            <div class="row mt-3 d-flex align-items-center form-check pl-0">
                <div class="col-lg-6">
                    <h3>
                        <label for="proposalBid">
                            <strong>
                                Bids Public
                            </strong>
                        </label>
                        <input type="checkbox" name="proposalBid" @if($proposal->bid_public) checked @endif>
                    </h3>
                    <h6 class="text-muted"  style="font-size: small">
                        If checked, the bids will be available in the proposal page for every user
                    </h6>
                </div>
            </div>

            <div style="border: 1px solid gray" class="mt-3"></div>

            <div class="row m-3 d-flex align-items-center justify-content-end">
                <div class="col-lg-2 m-2">
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </div>
                <div class="col-lg-2 m-2">
                    <a href="{{ route('proposal', $proposal->id) }}" class="btn btn-secondary w-100">Cancel</a>
                </div>
            </div>
        
        </form>

    </div>
</main>

@endsection