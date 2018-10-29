<div class="col-md-3 teamItem"  data-id="{{$team->id}}">
    <a href="{{ url('team/')}}/{{$team->id}}" class="list-group-item-action">
        <div class="card mb-4 box-shadow">
            <div class="card-body">
                <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger">{{ $team->teamname }} </p>
            </div>
        </div>
    </a>
</div>
