
<form method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}
    <div class="modal-body">
        <div class="form-group">
            <label for="email">E-mail</label>
        <input class="form-control" id="email" name="email" type="email" placeholder="Your E-mail adress"  value="{{ old('email') }}" required>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary btn-block" type="submit">
            RETRIEVE PASSWORD
        </button>
    </div>
</form>
