@extends('layouts.app')

@section('title', 'Create proposal')

@section('content')
    <!-- Content create proposal -->
    <div class="container-fluid bg-white">
    <div class="bg-white mb-0 mt-4 pt-4 panel">
        <h4>
            <i class="fa fa-plus"></i> Create a Proposal</h4>
    </div>
    <hr id="hr_space" class="mt-2">
    <main>
        <form class="ml-4 mr-4" method="POST" action="{{ route('create') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="form-group col-md-12">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required>
                @if ($errors->has('title'))
                  <span class="error">
                    {{ $errors->first('title') }}
                  </span>
                @endif
            </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6"> <!-- todo: get database skills -->
                    <label for="skill">Skill</label>
                    <select id="skill" name="skill[]" class="form-control" value="{{ old('skill') }}" multiple required>
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
                    <select id="faculty" name="faculty[]" class="form-control" value="{{ old('faculty') }}" multiple required>
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


            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" cols="30" class="form-control" placeholder="Describe this proposal: things you may consider important." value="{{ old('description') }}" required></textarea>
                    @if ($errors->has('description'))
                      <span class="error">
                        {{ $errors->first('description') }}
                      </span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><i class="fa fa-clock"></i> Duration</label>
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="days">Days</label>
                            <select id="days" name="days" class="form-control" required>
                                <option value="">&nbsp;</option>
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                            </select>
                            @if ($errors->has('images'))
                            <span class="error">
                            {{ $errors->first('images') }}
                          </span>
                            @endif
                        </div>
                        <div class="form-group col-md-2">
                            <label for="hours">Hours</label>
                            <select id="hours" name="hours" class="form-control" required>
                                <option value="">&nbsp;</option>
                                <option>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                <option>13</option>
                                <option>14</option>
                                <option>15</option>
                                <option>16</option>
                                <option>17</option>
                                <option>18</option>
                                <option>19</option>
                                <option>20</option>
                                <option>21</option>
                                <option>22</option>
                                <option>23</option>
                                <option>24</option>
                            </select>
                            @if ($errors->has('hours'))
                            <span class="error">
                            {{ $errors->first('hours') }}
                          </span>
                            @endif
                        </div>
                        <div class="form-group col-md-3">
                            <label for="minutes">Minutes</label>
                            <input id="minutes" class="form-control" type="number" name="minutes" min="0" max="59" required value="0">
                            @if ($errors->has('minutes'))
                            <span class="error">
                            {{ $errors->first('minutes') }}
                          </span>
                            @endif
                        </div>
                    </div>
                </div>


            <div class="form-group col-md-6"> <!-- todo triggers to database-->
                <label><i class="fa fa-calendar"></i> Dates</label>
                <div class="form-row">
                <div class="form-group col-md-4">
                <label for="start">Announce winner</label>
                <input type="date" name="announce" required>
                </div>
                <div class="form-group col-md-4">
                <label>Due of project  </label>
                <input type="date" name="due" required>
                </div>
                </div>
            </div>

            </div>

            <div class="form-row">
            <div class="form-group col-md-6">
                    <label><i class="fa fa-stop-circle"></i> Privacy</label>
                    <div class="form-row">
                        <div class="checkbox col-md-6">
                            <label for="agree">
                                <input id="public_prop[]" name="public_prop" type="checkbox">
                                Public Proposal</label>
                        </div>
                        <div class="checkbox col-md-6">
                            <label for="agree">
                                <input id="public_bid[]" name="public_bid" type="checkbox">
                                Public Bid</label>
                        </div>
                    </div>
                </div>
            </div>


                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary col-md-12">Create proposal</button>
                </div>

        </form>

    </main>
</div>
</div>
@endsection
