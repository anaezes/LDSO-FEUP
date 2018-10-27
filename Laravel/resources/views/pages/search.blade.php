@extends('layouts.app')

@section('title', 'Search')

@section('content')

<div class="content p-4">
    <div class="bg-white mb-0 mt-4 panel">
        <h4>
            <i class="fa fa-search-plus"></i> Search results</h4>
    </div>
    <hr id="hr_space" class="mt-2">
    <main>
        <a class="btn btn-primary col-md-12 mb-4" data-toggle="collapse" href="#advSearch" aria-expanded="false" aria-controls="advSearch">
        Advanced search options
        </a>
        <form class="ml-4 mr-4 collapse" id="advSearch">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Title</label>
                    <input type="text" name="title" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Skills</label>
                    <input type="text" name="skill" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Faculty</label>
                    <select class="form-control" name="faculty">
                        <option value="">&nbsp;</option>
                        <option>Arts&amp;Music</option>
                        <option>Biographies</option>
                        <option>Business</option>
                        <option>Kids</option>
                        <option>Comics</option>
                        <option>Cooking</option>
                        <option>Computation&amp;Tech</option>
                        <option>Education</option>
                        <option>Health&amp;Fitness</option>
                        <option>History</option>
                        <option>Horror</option>
                        <optgroup label="Literature">
                            <option>All </option>
                            <option>Anthologies </option>
                            <option>Classics </option>
                            <option>Contemporary </option>
                            <option>Sci-Fi&amp;Fantasy </option>
                            <option>Romance </option>
                            <option>Crime </option>
                        </optgroup>
                        <option>Religion </option>
                        <option>Science </option>
                        <option>Self-Help </option>
                        <option>Travel </option>
                        <option>Other </option>
                    </select>
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" id="advSearchSubmit" class="btn btn-primary col-md-12">Search</button>
                </div>
            </div>
        </form>

        <section class="jumbotron text-center p-1 m-2" style="background-color: lightgrey">
            <div class="container">
                <h3 id="responseSentence" class="jumbotron-heading m-4">{{ $responseSentence }}</h3>
            </div>
        </section>

        <!--auctions partial-->
        @include('pages.auctionItems', ['auctions' => $auctions])

    </main>
</div>
</div>

@endsection
