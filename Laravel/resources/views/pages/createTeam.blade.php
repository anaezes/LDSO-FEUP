 <form method="POST" action="{{ route('createTeam') }}" enctype="multipart/form-data">
     {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Team Name</label>
                        <input class="form-control" id="teamName" name="teamName" type="text" placeholder="Your Team's Name" required>
                    </div>
                    <div class="form-group">
                        <label for="usernameR">Members</label>
                        <input class="form-control" id="members" name="members[]" type="text" placeholder="Search for members to add to your new team" >
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary btn-block" type="submit">Create team</button>
                    </div>
            </form>
        </div>
    </div>
</div>


