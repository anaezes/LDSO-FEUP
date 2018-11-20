@extends('layouts.app')

@section('title', 'bid')

@section('content')

<!-- proposal Content -->
<main  data-id="{{$bid}}">
    <div class="container p-5">
        <div id="bidResult" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="bidResultHeader" class="modal-title align-self-center">.</h4>
                    </div>
                    <div class="modal-body">
                        <p id="bidResultBody" class="alert alert-danger"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                    </div>
                </div>

            </div>
        </div>
        <table class="table" style="border: none">
            <tbody style="border: none">
            <tr>
                <td colspan="2" style="border: none" > <strong style="font-size: xx-large">Bid</strong></td>
            </tr>

            <tr>
                <td><strong>Description</strong></td>
                <td>{{$bid->description}}</td>
            </tr>

            <tr>
                <td><strong>Due date</strong></td>
                <td>{{$bid->submissiondate}}</td>
            </tr>
            <tr>
                <td><strong>Team</strong></td>
                <td><a class="button btn btn-sm btn-outline-secondary p-2 " href="{{ url("team/{$bid->idteam}") }}">
                    <b>
                        <i class="fa fa-users"></i>
                        <p> {{$bid->team->teamname}}</p>
                    </b></a></td>
            </tr>
            <tr>
                <td style="border: none; width: 150px;"><strong>Faculty Leader</strong></td>
                <td>
                     <p>{{$bid->facultyLeader->facultyname}}</p>
                 </td>
            </tr>

            <tr>
                <td style="border: none; width: 200px;"><strong>Team members</strong></td>
                <td>
                @foreach ($bid->facultysMembers as $fac)
                <p>{{$fac->facultyname}}</p>
                @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Skills</strong></td>
                <td>
                    @foreach ($bid->skills as $skill)
                    <p>{{$skill}}</p>
                    @endforeach
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</main>

@endsection