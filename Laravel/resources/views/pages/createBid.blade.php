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
        <form class="ml-4 mr-4" method="POST" action="{{ route ('createBid', ['id' => $id]) }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="descriptionBid">Description</label>
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
                        <option value="1">team 1</option>
                        <option value="2">team 2</option>
                        <option value="3">team 3</option>
                        <option value="4">team 4</option>
                    </select>
                    @if ($errors->has('team'))
                    <span class="error">
                        {{ $errors->first('team') }}
                      </span>
                    @endif
                </div>

                <div class="form-group col-md-2">
                </div>

                <div class="form-group col-md-6"> <!-- todo triggers to database-->
                    <label><i class="fa fa-calendar"></i> Date of submission</label>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                    <input type="date" name="submissionDate" required>
                        </div>
                    </div>
                </div>


                <div class="form-group col-md-12">
                    <p><br><br>
                    <button type="submit" class="btn btn-primary col-md-12">Create Bid</button>
                    </p>
                </div>
            </div>



        </form>
    </main>
</div>
</div>
@endsection
