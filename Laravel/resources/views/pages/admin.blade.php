@extends('layouts.app')

@section('title', 'Administrator')

@section('content')

 <!-- Admin dashboard -->
        <div class="container-fluid bg-white">
            <!-- Title-->
            <div class="bg-white danimb-0 mt-4 panel">
                <h5>
                    <i class="fa fa-gavel"></i> Manage users </h5>
            </div>
            <form class="ml-4 mr-4">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="username">Select an user</label>
                        <input id="usernameInput" name="username" class="form-control" type="text" placeholder="username">
                    </div>
                </div>
                <a class="btn btn-primary col-md-12 mb-4" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Take an action
                </a>
                <div class="collapse" id="collapseExample">
                    <span  class="btn col-md-12 mb-2 btn-outline-secondary adminBtn" onclick="adminAction('visit_profile',-1,document.getElementById('usernameInput').value)">Visit user profile</span>
                    <span  class="btn col-md-12 mb-2 btn-outline-secondary adminBtn" onclick="adminAction('suspend',-1,document.getElementById('usernameInput').value)">Suspend user indefinitely</span>
                    <span class="btn col-md-12 mb-2 btn-outline-secondary adminBtn" onclick="adminAction('normal',-1,document.getElementById('usernameInput').value)">Reactivate suspended user</span>
                    <span  class="btn col-md-12 mb-2 btn-outline-secondary adminBtn" onclick="adminAction('ban',-1,document.getElementById('usernameInput').value)">Ban user permanently</span>
                    <span class="btn col-md-12 mb-2 btn-outline-secondary adminBtn" onclick="adminAction('promote',-1,document.getElementById('usernameInput').value)">Promote user to moderator</span>
                    <span class="btn col-md-12 mb-2 btn-outline-secondary adminBtn" onclick="adminAction('normal',-1,document.getElementById('usernameInput').value)">Revoke moderator privileges</span>
                </div>
            </form>
            <div class="bg-white mb-0 mt-4 panel">
                <h5>
                    <i class="fa fa-gavel"></i> Account deletion requests </h5>
            </div>

            <hr id="hr_space" class="mt-2">

            <div class="my-3">


                @each('partials.adminAccountDeletionRequest', $delRequests, 'delRequest')

            </div>
        </div>
    </div>
@endsection
