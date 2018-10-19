@extends('layouts.app')

@section('title', 'Search')

@section('content')

@endsection

<div class="container-fluid bg-white">
    <main>
        <div class="bg-white mb-0 mt-5 panel p-1">
            <h4> 
                @if($action=="MY_proposalS")
                    <i class="fa fa-gavel"></i> My proposals
                @endif
                @if($action=="proposalS_IN")
                    <i class="fa fa-clock"></i> Proposals I'm in
                @endif
                @if($action=="HISTORY")
                    <i class="fa fa-history"></i> History 
                @endif
                @if($action=="WISHLIST")
                        <i class="fa fa-star"></i> Wish List 
                @endif
            </h4>
        </div>
        <hr id="hr_space" class="mt-2">
        @if($action!="WISHLIST")
        <div id="proposalsAlbum" class="album p-2">
        @else
        <div id="proposalsAlbum" class="list-group panel">
        @endif
          

        </div>
        <a href="#" id="showmorebutton" class="btn btn-outline-primary my-2 btn-block">Show More</a>
    </main>
</div>