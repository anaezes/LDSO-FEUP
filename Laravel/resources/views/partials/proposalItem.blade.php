<div class="col-md-3 proposalItem"  data-id="{{$proposal->id}}">
    <a href="{{ url('proposal/')}}/{{$proposal->id}}" class="list-group-item-action">
        <div class="card mb-4 box-shadow">
            <div class="card-body">
                <p class="card-text text-center hidden-p-md-down font-weight-bold" style="font-size: larger">{{ $proposal->title }} </p>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-star btn btn-sm text-primary"></i>
                    <small class="text-success">{{$proposal->bidValue}}</small>
                    <small class="text-danger">{{$proposal->timestamp}}</small>
                </div>
            </div>
        </div>
    </a>
</div>
