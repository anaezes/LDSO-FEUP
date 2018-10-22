<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
  {{ csrf_field() }}
  <div class="modal-body">
    <div class="form-group">
      <label for="name">Name</label>
      <input class="form-control" id="name" name="name" type="text" placeholder="Your Complete Name" value="{{ old('name') }}" required>
    </div>
    <div class="form-group">
      <label for="usernameR">Username</label>
      <input class="form-control" id="usernameR" name="username" type="text" placeholder="Username" value="{{ old('username') }}" required>
    </div>
    <div class="form-group">
      <label for="email">Email Address</label>
      <input class="form-control" id="emailRegister" name="email" type="email" placeholder="example@email.com" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
          <label for="idfaculty">Faculty</label>
          <select class="form-control" id="idfaculty" name="idfaculty" required>
                <option value="">Your Faculty</option>
                <option value="1" {{ old('idfaculty') == 1 ? 'selected' : '' }}>Faculty of Architecture</option>
                <option value="2" {{ old('idfaculty') == 2 ? 'selected' : '' }}>Faculty of Fine Arts</option>
                <option value="3" {{ old('idfaculty') == 3 ? 'selected' : '' }}>Faculty of Sciences</option>
                <option value="4" {{ old('idfaculty') == 4 ? 'selected' : '' }}>Faculty of Nutrition and Food Science</option>
                <option value="5" {{ old('idfaculty') == 5 ? 'selected' : '' }}>Faculty of Sports</option>
                <option value="6" {{ old('idfaculty') == 6 ? 'selected' : '' }}>Faculty of Law</option>
                <option value="7" {{ old('idfaculty') == 7 ? 'selected' : '' }}>Faculty of Economics</option>
                <option value="8" {{ old('idfaculty') == 8 ? 'selected' : '' }}>Faculty of Engineering</option>
                <option value="9" {{ old('idfaculty') == 9 ? 'selected' : '' }}>Faculty of Pharmacy</option>
                <option value="10" {{ old('idfaculty') == 10 ? 'selected' : '' }}>Faculty of Arts</option>
                <option value="11" {{ old('idfaculty') == 11 ? 'selected' : '' }}>Faculty of Medicine</option>
                <option value="12" {{ old('idfaculty') == 12 ? 'selected' : '' }}>Faculty of Dental Medicine</option>
                <option value="13" {{ old('idfaculty') == 13 ? 'selected' : '' }}>Faculty of Psychology and Education Science</option>
                <option value="14" {{ old('idfaculty') == 14 ? 'selected' : '' }}>Abel Salazar Institute of Biomedical Science</option>
                <option value="15" {{ old('idfaculty') == 15 ? 'selected' : '' }}>Porto Business School</option>
            </select>
    </div>
    <div class="form-group">
      <label for="phone">Phone Number</label>
      <input class="form-control" id="phone" name="phone" type="tel" placeholder="Your phone number" value="{{ old('phone') }}"  required>
    </div>
    <div class="form-group">
      <div class="form-row">
        <div class="col-md-6">
          <label for="passwordR">Password</label>
          <input class="form-control" id="passwordR" name="password" type="password" placeholder="Your password" required>
        </div>
        <div class="col-md-6">
          <label for="password-confirm">Confirm Password</label>
          <input class="form-control" id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm password" required>
        </div>
      </div>
    </div>
    </div>
  <div class="modal-footer">
    <button class="btn btn-primary btn-block" type="submit">REGISTER</button>
  </div>
</form>
