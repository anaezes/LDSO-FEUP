<div class="col-lg-2 mb-3">
    <div class="card" style="width: 18rem; height: 11rem;">
        <a href="{{ route('profile', $users[$i]) }}" style="text-decoration: none;">
            <div class="card-header">
                <span>@</span>{{ $users[$i]->username }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $users[$i]->name }}</h5>
                <h6 class="card-subtitle mb-2 text-muted">{{ $users[$i]->faculty->facultyname }}</h6>
            </div>
        </a>
    </div>
</div>
