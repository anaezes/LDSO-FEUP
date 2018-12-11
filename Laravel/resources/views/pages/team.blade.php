@extends('layouts.app')

@section('title', $team->teamname)

@section('content')
<!-- Team Content -->
<main>
  <div class="container mb-5 mt-5">
    <?php
      $bids = App\Bid::where('idteam', $team->id)->get();
    ?>
    <!-- Header -->
    <div class="row">
      <div class="col-lg-8">
        <h1><strong>{{ $team->teamname }}</strong></h1>
      </div>
      @if(Auth::user()->id == $team->user->id)
        <div class="col-lg-4">
          <div class="btn-group" role="group" aria-label="Manage team">
            @if($bids->isEmpty())
              <a data-toggle="modal" href="#" data-target="#myModalDeleteTeam" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete team">
                  <i class="far fa-trash-alt"></i>
              </a>
              <div style="border: 3px solid white"></div>
            @endif
            <a data-toggle="modal" href="#" data-target="#myModalEditTeam" class="btn btn-secondary" data-toggle="tooltip" data-placement="bottom" title="Edit team">
              <i class="fas fa-edit"></i> Edit
            </a>
            <div style="border: 3px solid white"></div>
            <a data-toggle="modal" href="#" data-target="#myModalAddMember" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Add team member">
                <i class="fas fa-user-plus"></i> Add member
            </a>
          </div>
          @if($bids->isEmpty())
            <!-- Modal Delete Team -->
            <div class="modal fade" id="myModalDeleteTeam" tabindex="-1" role="dialog" aria-labelledby="myModalDeleteTeamLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="myModalTeamLabel">Delete Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form action="{{ route('team.destroy', $team->id) }}" method="POST">
                    {!! method_field('DELETE') !!}
                    {!! csrf_field() !!}
                    <div class="modal-body">
                      <h5>Are you sure you want to delete this team?</h5>
                      <h6 style="color:red">This operation can not be undone!</h6>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          @endif
          <!-- Modal Edit Team -->
          <div class="modal fade" id="myModalEditTeam" tabindex="-1" role="dialog" aria-labelledby="myModalEditTeamLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="myModalEditTeamLabel">Edit Team</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('team.update', $team->id) }}" method="POST">
                  {!! method_field('PUT') !!}
                  {{ csrf_field() }}
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="teamname"><strong>Team's name</strong></label>
                      <input type="text" name="teamname" class="w-100 mb-2" placeholder="Team's name" value="{{ $team->teamname }}" maxlength="70" required>
                    </div>
                    <div class="form-group">
                      <label for="teamFaculty">Faculty Afiliation</label>
                      <select class="form-control" name="teamfaculty[]" multiple>
                        @foreach(App\Faculty::get() as $faculty)
                          <option value="{{ $faculty->id }}" @foreach($team->faculties as $tf) @if($faculty->id == $tf->id)selected="selected"@endif @endforeach>{{ $faculty->facultyname }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="teamdescription" class="mt-2"><strong>Team's description</strong></label>
                      <textarea name="teamdescription" rows="3" cols="40" class="w-100" maxlength="200" placeholder="Team's description" required>{{ $team->teamdescription }}</textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="Add" class="btn btn-primary"><span class="fas fa-save"></span> Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span> Close</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <!-- Modal Add Member -->
          <div class="modal fade" id="myModalAddMember" tabindex="-1" role="dialog" aria-labelledby="myModalAddMemberLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="myModalTeamLabel">Add Member</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form action="{{ route('team.addMember', $team->id) }}" method="POST">
                  {{ csrf_field() }}
                  <div class="modal-body">
                    <label for="usernameATM">Username</label>
                    <input type="text" name="username" id="usernameATM" class="w-100" placeholder="User's username" required>
                    <span class="input-group-append">
                      <a data-toggle="modal" href="#myModalSearchMember" data-target="#myModalSearchMember" class="btn btn-primary" title="Search team member">
                          <i class="fas fa-search"></i> Advanced Search</a>
                    </span>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="Add" class="btn btn-primary"><span class="fas fa-plus"></span> Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="fas fa-times"></span> Close</button>
                  </div>
                </form>
              </div>
            </div>
            <!-- Modal Search Member -->
            <div class="modal fade" id="myModalSearchMember" tabindex="-1" role="dialog" aria-labelledby="myModalSearchMemberLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myModalTeamLabel">Search Member</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="ml-4 mr-4" method="POST" action="{{ route('searchMember') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="skill">Skill</label>
                                        <select id="skill" name="skill[]" class="form-control"  multiple>
                                            <option>Skill1</option>
                                            <option>Skill2</option>
                                            <option>Skill3</option>
                                            <option>Skill4</option>
                                        </select>
                                        @if ($errors->has('skill'))
                                        <span class="error">
                                        {{ $errors->first('skill') }}
                                      </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="faculty">Faculty</label>
                                        <select id="faculty" name="faculty[]" class="form-control"  multiple>
                                            <option>Faculty of Architecture</option>
                                            <option>Faculty of Fine Arts</option>
                                            <option>Faculty of Science</option>
                                            <option>Faculty of Sport</option>
                                            <option>Faculty of Law</option>
                                            <option>Faculty of Economics</option>
                                            <option>Faculty of Engineering</option>
                                            <option>Faculty of Pharmacy</option>
                                            <option>Faculty of Arts</option>
                                            <option>Faculty of Medicine</option>
                                            <option>Faculty of Dental Medicine</option>
                                            <option>Faculty of Psychology and Education Science</option>
                                            <option>Abel Salazar Institute of Biomedical Science</option>
                                            <option>Porto Business School</option>
                                        </select>
                                        @if ($errors->has('faculty'))
                                        <span class="error">
                                        {{ $errors->first('faculty') }}
                                      </span>
                                        @endif
                                    </div>
                                </div>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-search"></i>Search
                                </button>
                        </div>

                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
      @endif
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Leader -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Leader</strong></h3>
      </div>
      <div class="col-lg-9">
        <h3>{{ $team->user->name }}</h3>
      </div>
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Afiliation -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Afiliation</strong></h3>
      </div>
      <div class="col-lg-9">
        @foreach($team->faculties as $faculty)
          <h3>{{ $faculty->facultyname }}</h3>
        @endforeach
      </div>
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Team Grade -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Grade</strong></h3>
      </div>
      <div class="col-lg-9">
        <h3>
          4.5 / 5.0
        </h3>
      </div>
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Description -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Description</strong></h3>
      </div>
      <div class="col-lg-9" style="word-wrap: break-word">
        <h3>{{ $team->teamdescription }}</h3>
      </div>
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Badges -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Badges</strong></h3>
      </div>
      <div class="col-lg-9">
        <h3>
          <span class="fab fa-angular"></span>
          <span class="fab fa-apple"></span>
        </h3>
      </div>
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Members -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Members</strong></h3>
      </div>
      <div class="col-lg-9">
        @foreach($team->members as $member)
        <div class="row mb-2">
          <div class="col-lg-9">
            <h3>
              <a href="{{ url("profile/{$member->id}") }}">
                {{ $member->name }}
              </a>
            </h3>
          </div>
          <div class="col-lg-3">
            @if($member->id == $team->user->id)
              <span class="badge badge-pill badge-info ml-3">Leader</span>
            @else
              <span class="badge badge-pill badge-light ml-3">Member</span>
              @if(Auth::id() == $team->user->id)
              <a href="#" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="{{ '#removeMember'.$member->id }}">
                <span class="fas fa-times"></span>
              </a>
              <div class="modal fade" id="{{ 'removeMember'.$member->id }}" tabindex="-1" role="dialog" aria-labelledby="removeMemberModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="removeMemberModalLabel">Remove member</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{ route('team.removeMember', $team->id) }}" method="POST">
                      {!! method_field('delete') !!}
                      {!! csrf_field() !!}
                      <div class="modal-body">
                        <h5>Are you sure you want to remove {{ $member->name }} from the team?</h5>
                        <h6 class="text-muted">{{ $member->name }} can be added again using the 'add member' button</h6>
                        <input type="hidden" name="memberId" value="{{ $member->id }}">
                        <input type="hidden" name="source" value="leader">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Remove</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @elseif (Auth::id() == $member->id)
              <a href="#" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#removeMember">
                Leave
              </a>
              <div class="modal fade" id="removeMember" tabindex="-1" role="dialog" aria-labelledby="removeMemberModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="removeMemberModalLabel">Remove member</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="{{ route('team.removeMember', $team->id) }}" method="POST">
                      {!! method_field('delete') !!}
                      {!! csrf_field() !!}
                      <div class="modal-body">
                        <h5>Are you sure you want to leave the team?</h5>
                        <input type="hidden" name="memberId" value="{{ $member->id }}">
                        <input type="hidden" name="source" value="member">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Leave</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              @endif
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <div style="border: 1px solid gray" class="mt-3"></div>

    <!-- Participations -->
    <div class="row mt-3">
      <div class="col-lg-3">
        <h3><strong>Participations</strong></h3>
      </div>
      <div class="col-lg-9">
        @foreach($bids as $bid)
          <div class="row mb-2">
            <div class="col-lg-9">
              <h3>
                <a href="{{ url("proposal/{$bid->idproposal}") }}">
                  {{ $bid->proposal->title }}
                </a>
              </h3>
            </div>
            <div class="col-lg-3">
              @if($bid->winner == true)
                <span class="badge badge-pill badge-warning ml-3">Winner</span>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</main>
@endsection
