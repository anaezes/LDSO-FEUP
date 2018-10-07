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
                <label for="title">Title</label>
                <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required>
                @if ($errors->has('title'))
                  <span class="error">
                    {{ $errors->first('title') }}
                  </span>
                @endif

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="language">Skills</label>
                    <select id="language" name="language" class="form-control" value="{{ old('language') }}" required>
                        <option value="">&nbsp;</option>
                        <option>English</option>
                        <option>Afar</option>
                        <option>Abkhazian</option>
                        <option>Afrikaans</option>
                        <option>Amharic</option>
                        <option>Arabic</option>
                        <option>Assamese</option>
                        <option>Aymara</option>
                        <option>Azerbaijani</option>
                        <option>Bashkir</option>
                        <option>Belarusian</option>
                        <option>Bulgarian</option>
                        <option>Bihari</option>
                        <option>Bislama</option>
                        <option>Bengali/Bangla</option>
                        <option>Tibetan</option>
                        <option>Breton</option>
                        <option>Catalan</option>
                        <option>Corsican</option>
                        <option>Czech</option>
                        <option>Welsh</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="faculty">Faculty</label>
                    <select id="faculty" name="faculty"  class="form-control" required>
                        <option selected>Faculty of Architecture</option>
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
                    @if ($errors->has('lang'))
                      <span class="error">
                        {{ $errors->first('lang') }}
                      </span>
                    @endif
                </div>
            </div>


            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" cols="30" class="form-control" placeholder="Describe the book, its condition, or any other things you may consider important" value="{{ old('description') }}" required></textarea>
                    @if ($errors->has('description'))
                      <span class="error">
                        {{ $errors->first('description') }}
                      </span>
                    @endif
                </div>
            </div>
            <div class="form-row">

            </div>

            <label><i class="fa fa-clock"></i> Duration</label>
            <div class="form-row">
                <div class="form-group col-md-1.5">
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
                <div class="form-group col-md-1.5">
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
                <div class="form-group col-md-1">
                    <label for="minutes">Minutes</label>
                    <input id="minutes" class="form-control" type="number" name="minutes" min="0" max="59" required value="0">
                    @if ($errors->has('minutes'))
                      <span class="error">
                        {{ $errors->first('minutes') }}
                      </span>
                    @endif
                </div>
            </div>


            <div class="form-row">
                <div class="checkbox col-md-6">
                    <label for="agree">
                        <input id="agree" name="agree" type="checkbox" value="{{ old('agree') }}" required>
                        @if ($errors->has('isbn'))
                        <span class="error">
                          {{ $errors->first('isbn') }}
                        </span>
                        @endif
                        Public Proposal</label>
                </div>
                <div class="checkbox col-md-6">
                    <label for="agree">
                        <input id="agree" name="agree" type="checkbox" value="{{ old('agree') }}" required>
                        @if ($errors->has('isbn'))
                        <span class="error">
                          {{ $errors->first('isbn') }}
                        </span>
                        @endif
                        Public Bid</label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary col-md-12">Create proposal</button>
                </div>
            </div>
        </form>

    </main>
</div>
</div>
@endsection
