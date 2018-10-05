
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group">
            <label for="username">Username</label>
        <input class="form-control" id="username" name="username" type="text" placeholder="Your Username"  value="{{ old('username') }}" required>
        </div>
        <div class="form-group">
            <label for="password" >Password</label>
            <input class="form-control" id="password" type="password" name="password" required placeholder="Your password">
        </div>
        <div class="form-group">
            <div class="form-check">
                <label class="form-check-label" for="remember">
                    <input id="remember" class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
            </label>
            </div>
        </div>
        <div><a data-toggle="modal" data-dismiss="modal" href="#" data-target="#myModalForgotPassword">Forgot password</a></div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-block" type="submit">
            LOGIN
        </button>
    </div>
</form>
