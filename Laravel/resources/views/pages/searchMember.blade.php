@extends('layouts.app')

@section('title', 'SearchMember')

@section('content')

<div class="content p-4">
    <div class="bg-white mb-0 mt-4 panel">
        <h4>
            <i class="fa fa-search-plus"></i> Search results</h4>
    </div>
    <hr id="hr_space" class="mt-2">
    <main>
        <a class="btn btn-primary col-md-12 mb-4" data-toggle="collapse" href="#searchMem" aria-expanded="false" aria-controls="advSearch">
        Advanced search options
        </a>
        <form class="ml-4 mr-4 collapse" id ="searchMem" method="POST" action="{{ route('searchMember') }}" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Skills</label>
                    <select id="skill" name="skill[]" class="form-control" multiple>
                        <option>Skill1</option>
                        <option>Skill2</option>
                        <option>Skill3</option>
                        <option>Skill4</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="font-weight-bold">Faculty</label>
                    <select id="faculty" name="faculty[]" class="form-control" multiple>
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
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary col-md-12">Search</button>
                </div>
            </div>
        </form>



        <!--auctions partial-->
        @include('pages.memberItems', ['members' => $members])

    </main>
</div>
</div>

@endsection
