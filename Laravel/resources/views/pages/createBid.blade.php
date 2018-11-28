@extends('layouts.app')

@section('title', 'Create bid')

@section('content')
<!-- Content create proposal -->
<div class="container-fluid bg-white">
    <div class="bg-white mb-0 mt-4 pt-4 panel">
        <h4>
            <i class="fa fa-plus"></i> Create a Bid</h4>
    </div>
    <hr id="hr_space" class="mt-2">
    <main>
        <form class="ml-4 mr-115" method="POST" action="{{ route ('createBid', ['id' => $id ]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-row">
                <div class="form-group col-md-10">
                    <label for="descriptionBid"><i class="fa fa-align-justify"></i> Description</label>
                    <textarea id="descriptionBid" name="descriptionBid" rows="10" cols="30" class="form-control" placeholder="Describe your bid proposal: How will you solve our problem?" value="{{ old('description') }}" required></textarea>
                    @if ($errors->has('descriptionBid'))
                    <span class="error">
                        {{ $errors->first('descriptionBid') }}
                      </span>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3"> <!-- todo: get database skills -->
                    <label for="team"><i class="fa fa-calendar"></i> Teams</label>
                    <select name="team" class="form-control" value="{{ old('team') }}" required>
                    @if (Auth::check())
                        @foreach (Auth::user()->teams as $team)
                        <option value="{{$team->id}}" >{{$team->teamname}}</option>
                        @endforeach
                        @if(sizeof(Auth::user()->teams) == 0)
                        <option value="0" > "Please create a team first..."</option>
                        @endif
                    @endif
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><i class="fa fa-calendar"></i> Date of submission</label>

                    <input type="date" name="submissionDate" required>
                    </div>
                </div>


                <div class="form-group col-md-12">
                    <p><br><br>
                        @if(sizeof(Auth::user()->teams) == 0)
                        <button id="unbiddable" type="submit" disabled class="btn btn-outline-secondary col-md-6">Unable to bid, create a team first...</button>
                        @else
                        <button type="submit" class="btn btn-primary col-md-12">Create Bid</button>
                        @endif
                    </p>
                </div>
            </div>



        </form>
    </main>
</div>
</div>
@endsection
