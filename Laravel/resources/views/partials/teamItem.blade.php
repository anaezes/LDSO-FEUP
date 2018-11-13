<div class="col-md-2 teamItem"  data-id="{{$team->id}}">
  <a href="{{ url('team/') }}/{{ $team->id }}" class="list-group-item-action">
    <div class="card box-shadow" style="width: 18rem;">
      <div class="card-body">
        <h5  class="card-title font-weight-bold">{{ $team->teamname }}</h5>
        <h6 class="card-subtitle mb-2 mt-2 text-muted">{{ $team->user->name }}</h6>
        <p class="card-text">{{ $team->teamdescription }}</p>
      </div>
    </div>
  </a>
</div>
