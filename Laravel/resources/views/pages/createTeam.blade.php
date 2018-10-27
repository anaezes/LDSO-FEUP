<div class="modal fade" id="myModalTeam" tabindex="-1" role="dialog" aria-labelledby="myModalTeamLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalTeamLabel">Create Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('create_team') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Team Name</label>
                        <input class="form-control" id="name" name="name" type="text" placeholder="Your Complete Name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="usernameR">Members</label>
                        <input class="form-control" id="members" name="members[]" type="text" placeholder="Search for members to add to your new team" value="{{ old('members') }}">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary btn-block" type="submit">Create team</button>
                    </div>
            </form>
        </div>
    </div>
</div>
