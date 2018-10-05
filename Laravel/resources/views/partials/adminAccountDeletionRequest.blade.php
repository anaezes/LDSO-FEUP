    <a class="list-group-item list-group-item-action text-muted" id="dr-{{$delRequest->user->id}}">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 align-self-center text-left p-2 text-dark lead">
                    {{$delRequest->user->username}}
                </div>
                <div class="btn-group">
                    <span class="btn btn-sm btn-outline-secondary align-self-center p-2 m-2" onclick="location.href=' {{ url("profile/") }}/{{$delRequest->user->id}}'">Profile</span>
                </div>
                <div class="btn-group">
                    <span class="btn btn-sm btn-outline-secondary align-self-center p-2 m-2 " onclick="adminAction('remove_profile',{{$delRequest->user->id}})">Delete</span>
                </div>
                <div class="btn-group">
                    <span class="btn btn-sm btn-outline-secondary align-self-center p-2 m-2" onclick="adminAction('ignore_del_request',{{$delRequest->user->id}})">Ignore</span>
                </div>
            </div>
        </div>
    </a>


   