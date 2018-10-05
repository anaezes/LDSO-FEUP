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
      <label for="age">Age</label>
      <input class="form-control" id="age" name="age" type="number" placeholder="Age" value="{{ old('age') }}" required>
    </div>
    <div class="form-group">
      <label for="email">Email Address</label>
      <input class="form-control" id="emailRegister" name="email" type="email" placeholder="example@email.com" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
      <label for="address">Address</label>
      <input class="form-control" id="address" name="address" type="text" placeholder="Example Address, 4441" value="{{ old('address') }}" required>
    </div>
    <div class="form-group">
      <div class="form-row">
        <div class="col-md-6">
          <label for="postalcode">Postal Code</label>
          <input class="form-control" id="postalcode" name="postalcode" type="text" placeholder="XXXX-XXX" value="{{ old('postalcode') }}" required>
        </div>
        <div class="col-md-6">
          <label for="idcountry">Country</label>
          <select class="form-control" id="idcountry" name="idcountry" required>
            <option value="">Your country</option>
            <option value="1" {{ old('idcountry') == 1 ? 'selected' : '' }}>Austria</option>
            <option value="2" {{ old('idcountry') == 2 ? 'selected' : '' }}>Italy</option>
            <option value="3" {{ old('idcountry') == 3 ? 'selected' : '' }}>Belgium</option>
            <option value="4" {{ old('idcountry') == 4 ? 'selected' : '' }}>Latvia</option>
            <option value="5" {{ old('idcountry') == 5 ? 'selected' : '' }}>Bulgaria</option>
            <option value="6" {{ old('idcountry') == 6 ? 'selected' : '' }}>Lithuania</option>
            <option value="7" {{ old('idcountry') == 7 ? 'selected' : '' }}>Croatia</option>
            <option value="8" {{ old('idcountry') == 8 ? 'selected' : '' }}>Luxembourg</option>
            <option value="9" {{ old('idcountry') == 9 ? 'selected' : '' }}>Cyprus</option>
            <option value="10" {{ old('idcountry') == 10 ? 'selected' : '' }}>Malta</option>
            <option value="11" {{ old('idcountry') == 11 ? 'selected' : '' }}>Czech Republic</option>
            <option value="12" {{ old('idcountry') == 12 ? 'selected' : '' }}>Netherlands</option>
            <option value="13" {{ old('idcountry') == 13 ? 'selected' : '' }}>Denmark</option>
            <option value="14" {{ old('idcountry') == 14 ? 'selected' : '' }}>Indonesia</option>
            <option value="15" {{ old('idcountry') == 15 ? 'selected' : '' }}>Poland</option>
            <option value="16" {{ old('idcountry') == 16 ? 'selected' : '' }}>Estonia</option>
            <option value="17" {{ old('idcountry') == 17 ? 'selected' : '' }}>Portugal</option>
            <option value="18" {{ old('idcountry') == 18 ? 'selected' : '' }}>Finland</option>
            <option value="19" {{ old('idcountry') == 19 ? 'selected' : '' }}>Romania</option>
            <option value="20" {{ old('idcountry') == 20 ? 'selected' : '' }}>France</option>
            <option value="21" {{ old('idcountry') == 21 ? 'selected' : '' }}>Slovakia</option>
            <option value="22" {{ old('idcountry') == 22 ? 'selected' : '' }}>Germany</option>
            <option value="23" {{ old('idcountry') == 23 ? 'selected' : '' }}>Slovenia</option>
            <option value="24" {{ old('idcountry') == 24 ? 'selected' : '' }}>Greece</option>
            <option value="25" {{ old('idcountry') == 25 ? 'selected' : '' }}>Spain</option>
            <option value="26" {{ old('idcountry') == 26 ? 'selected' : '' }}>Hungary</option>
            <option value="27" {{ old('idcountry') == 27 ? 'selected' : '' }}>Sweden</option>
            <option value="28" {{ old('idcountry') == 28 ? 'selected' : '' }}>Ireland</option>
            <option value="29" {{ old('idcountry') == 29 ? 'selected' : '' }}>United Kingdom</option>
        </select>
        </div>
      </div>
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
