      <form method="POST" action="{{ route('team.store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal-body">
              <div class="form-group">
                <label for="teamName">Team Name</label>
                <input class="form-control" id="teamName" name="teamName" type="text" placeholder="Your Team's Name" required maxlength="100">
              </div>
              <div class="form-group">
                <label for="teamFaculty">Faculty Afiliation</label>
                <select class="form-control" id="teamFaculty" name="teamFaculty[]" multiple>
                  @foreach(App\Faculty::get() as $faculty)
                    <option value="{{ $faculty->id }}">{{ $faculty->facultyname }}</option>
                  @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label for="teamDescription">Description</label>
                <textarea name="teamDescription" id="teamDescription" rows="8" cols="40" class="w-100" maxlength="200" placeholder="Your Team's Description (max 200 chars)" required></textarea>
              </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary btn-block" type="submit">Create team</button>
        </div>
      </form>
    </div>
  </div>
</div>
