@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<form method="POST" action="{{ route('password.doreset') }}">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group">
        <label for="email">Token</label>
        <input class="form-control" type="text" name="token" value="{{ $token }}" required>
        <label for="email">E-mail</label>
        <input class="form-control" id="email" name="email" type="email" placeholder="Your e-mail" required>        
        <label for="passwordR">New Password</label>
        <input class="form-control" id="passwordR" name="password" type="password" placeholder="Your new password" required>
        <label for="password-confirm">Confirm New Password</label>
        <input class="form-control" id="password-confirm" type="password" name="password_confirmation" placeholder="Confirm new password" required>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-block" type="submit">
            RESET YOUR PASSWORD
        </button>
    </div>
</form>

@endsection