
@if(Auth::user()->id !== $member->id)
<div class="col-md-3 memberItem"  data-id="{{ $member->id }}">
    <a href="{{ url('profile/')}}/{{$member->id}}" class="list-group-item-action">
        <div class="card mb-4 box-shadow">
            <div class="card-body">
                <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger">{{ $member->username }} </p>
            </div>
        </div>
    </a>
</div>
@endif
