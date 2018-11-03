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
                @if($action=="ALL_proposalS")
                <i class="fa fa-gavel"></i> Proposals
                @endif
                @if($action=="proposalS_IN")
                    <i class="fa fa-clock"></i> Proposals I'm in
                @endif
                @if($action=="HISTORY")
                    <i class="fa fa-history"></i> History 
                @endif
                @if($action=="TEAMS")
                        <i class="fa fa-object-group"></i> My Teams
                @endif
            </h4>
        </div>
        <hr id="hr_space" class="mt-2">
        @if($action!="TEAMS")
        <div id="proposalsAlbum" class="album p-2">
        @else
        <div id="proposalsAlbum" class="list-group panel">
            <a data-toggle="modal" href="#" data-target="#myModalTeam">
                <i class="fa fa-fw fa-plus" title="Add a new Team"></i> New Team
            </a>
            <div class="modal fade" id="myModalTeam" tabindex="-1" role="dialog" aria-labelledby="myModalTeamLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalTeamLabel">Create Team</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @include('pages.createTeam')
        @endif

        </div>
        <a href="#" id="showmorebutton" class="btn btn-outline-primary my-2 btn-block">Show More</a>
    </main>
</div>