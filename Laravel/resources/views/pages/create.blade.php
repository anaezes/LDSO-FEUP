@extends('layouts.app')

@section('title', 'Create Auction')

@section('content')
    <!-- Content create auction -->
    <div class="container-fluid bg-white">
    <div class="bg-white mb-0 mt-4 pt-4 panel">
        <h4>
            <i class="fa fa-plus"></i> Create an Auction</h4>
    </div>
    <hr id="hr_space" class="mt-2">
    <main>
        <form class="ml-4 mr-4" method="POST" action="{{ route('create') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Book title</label>
                    <input id="title" name="title" type="text" class="form-control" value="{{ old('title') }}" required>
                    @if ($errors->has('title'))
                      <span class="error">
                        {{ $errors->first('title') }}
                      </span>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="author">Author</label>
                    <input id="author" name="author" type="text" class="form-control" value="{{ old('author') }}" required>
                    @if ($errors->has('author'))
                      <span class="error">
                        {{ $errors->first('author') }}
                      </span>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="isbn">ISBN</label>
                    <input id="isbn" name="isbn" type="text" class="form-control" value="{{ old('isbn') }}" required>
                    @if ($errors->has('isbn'))
                      <span class="error">
                        {{ $errors->first('isbn') }}
                      </span>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="publisher">Publisher</label>
                    <input id="publisher" name="publisher" type="text" value="{{ old('publisher') }}" class="form-control" required>
                    @if ($errors->has('publisher'))
                      <span class="error">
                        {{ $errors->first('publisher') }}
                      </span>
                    @endif
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="language">Language</label>
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
                        <option>Danish</option>
                        <option>German</option>
                        <option>Bhutani</option>
                        <option>Greek</option>
                        <option>Esperanto</option>
                        <option>Spanish</option>
                        <option>Estonian</option>
                        <option>Basque</option>
                        <option>Persian</option>
                        <option>Finnish</option>
                        <option>Fiji</option>
                        <option>Faeroese</option>
                        <option>French</option>
                        <option>Frisian</option>
                        <option>Irish</option>
                        <option>Scots/Gaelic</option>
                        <option>Galician</option>
                        <option>Guarani</option>
                        <option>Gujarati</option>
                        <option>Hausa</option>
                        <option>Hindi</option>
                        <option>Croatian</option>
                        <option>Hungarian</option>
                        <option>Armenian</option>
                        <option>Interlingua</option>
                        <option>Interlingue</option>
                        <option>Inupiak</option>
                        <option>Indonesian</option>
                        <option>Icelandic</option>
                        <option>Italian</option>
                        <option>Hebrew</option>
                        <option>Japanese</option>
                        <option>Yiddish</option>
                        <option>Javanese</option>
                        <option>Georgian</option>
                        <option>Kazakh</option>
                        <option>Greenlandic</option>
                        <option>Cambodian</option>
                        <option>Kannada</option>
                        <option>Korean</option>
                        <option>Kashmiri</option>
                        <option>Kurdish</option>
                        <option>Kirghiz</option>
                        <option>Latin</option>
                        <option>Lingala</option>
                        <option>Laothian</option>
                        <option>Lithuanian</option>
                        <option>Latvian/Lettish</option>
                        <option>Malagasy</option>
                        <option>Maori</option>
                        <option>Macedonian</option>
                        <option>Malayalam</option>
                        <option>Mongolian</option>
                        <option>Moldavian</option>
                        <option>Marathi</option>
                        <option>Malay</option>
                        <option>Maltese</option>
                        <option>Burmese</option>
                        <option>Nauru</option>
                        <option>Nepali</option>
                        <option>Dutch</option>
                        <option>Norwegian</option>
                        <option>Occitan</option>
                        <option>(Afan)/Oromoor/Oriya</option>
                        <option>Punjabi</option>
                        <option>Polish</option>
                        <option>Pashto/Pushto</option>
                        <option>Portuguese</option>
                        <option>Quechua</option>
                        <option>Rhaeto-Romance</option>
                        <option>Kirundi</option>
                        <option>Romanian</option>
                        <option>Russian</option>
                        <option>Kinyarwanda</option>
                        <option>Sanskrit</option>
                        <option>Sindhi</option>
                        <option>Sangro</option>
                        <option>Serbo-Croatian</option>
                        <option>Singhalese</option>
                        <option>Slovak</option>
                        <option>Slovenian</option>
                        <option>Samoan</option>
                        <option>Shona</option>
                        <option>Somali</option>
                        <option>Albanian</option>
                        <option>Serbian</option>
                        <option>Siswati</option>
                        <option>Sesotho</option>
                        <option>Sundanese</option>
                        <option>Swedish</option>
                        <option>Swahili</option>
                        <option>Tamil</option>
                        <option>Telugu</option>
                        <option>Tajik</option>
                        <option>Thai</option>
                        <option>Tigrinya</option>
                        <option>Turkmen</option>
                        <option>Tagalog</option>
                        <option>Setswana</option>
                        <option>Tonga</option>
                        <option>Turkish</option>
                        <option>Tsonga</option>
                        <option>Tatar</option>
                        <option>Twi</option>
                        <option>Ukrainian</option>
                        <option>Urdu</option>
                        <option>Uzbek</option>
                        <option>Vietnamese</option>
                        <option>Volapuk</option>
                        <option>Wolof</option>
                        <option>Xhosa</option>
                        <option>Yoruba</option>
                        <option>Chinese</option>
                        <option>Zulu</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="category">Category</label>
                    <select id="category" name="category"  class="form-control" required>
                        <option selected>Arts&amp;Music</option>
                        <option>Biographies</option>
                        <option>Business</option>
                        <option>Kids</option>
                        <option>Comics</option>
                        <option>Cooking</option>
                        <option>Computation&amp;Tech</option>
                        <option>Education</option>
                        <option>Health&amp;Fitness</option>
                        <option>History</option>
                        <option>Horror</option>
                        <optgroup label="Literature">
                            <option>Anthologies</option>
                            <option>Classics</option>
                            <option>Contemporary</option>
                            <option>Sci-Fi&amp;Fantasy</option>
                            <option>Romance</option>
                            <option>Crime</option>
                       </optgroup>
                        <option>Religion</option>
                        <option>Science</option>
                        <option>Self-Help</option>
                        <option>Travel</option>
                        <option>Other</option>
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
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="images[]">Image(s) of the book</label>
                    <input id="images" name="images[]" class="form-control" type="file" accept="image/*" multiple>
                    @if ($errors->has('images'))
                      <span class="error">
                        {{ $errors->first('images') }}
                      </span>
                    @endif
                </div>
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
                <div class="form-group col-md-12">
                    <textarea class="form-control col-md-12" rows="4" cols="100" readonly>
    Bookhub Terms of Service

    1. Terms
    By accessing the website at http://bookhub.com, you are agreeing to be bound by these terms of service, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this website are protected by applicable copyright and trademark law.

    2. Use License
        Permission is granted to temporarily download one copy of the materials (information or software) on Bookhub's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:
            modify or copy the materials;
            use the materials for any commercial purpose, or for any public display (commercial or non-commercial);
            attempt to decompile or reverse engineer any software contained on Bookhub's website;
            remove any copyright or other proprietary notations from the materials; or
            transfer the materials to another person or "mirror" the materials on any other server.
        This license shall automatically terminate if you violate any of these restrictions and may be terminated by Bookhub at any time. Upon terminating your viewing of these materials or upon the termination of this license, you must destroy any downloaded materials in your possession whether in electronic or printed format.

    3. Disclaimer
        The materials on Bookhub's website are provided on an 'as is' basis. Bookhub makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.
        Further, Bookhub does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its website or otherwise relating to such materials or on any sites linked to this site.

    4. Limitations
    In no event shall Bookhub or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Bookhub's website, even if Bookhub or a Bookhub authorized representative has been notified orally or in writing of the possibility of such damage. Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.

    5. Accuracy of materials
    The materials appearing on Bookhub website could include technical, typographical, or photographic errors. Bookhub does not warrant that any of the materials on its website are accurate, complete or current. Bookhub may make changes to the materials contained on its website at any time without notice. However Bookhub does not make any commitment to update the materials.

    6. Links
    Bookhub has not reviewed all of the sites linked to its website and is not responsible for the contents of any such linked site. The inclusion of any link does not imply endorsement by Bookhub of the site. Use of any such linked website is at the user's own risk.

    7. Modifications
    Bookhub may revise these terms of service for its website at any time without notice. By using this website you are agreeing to be bound by the then current version of these terms of service.

    8. Governing Law
    These terms and conditions are governed by and construed in accordance with the laws of Portugal and you irrevocably submit to the exclusive jurisdiction of the courts in that State or location.
    </textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="checkbox col-md-12">
                    <label for="agree">
                      <input id="agree" name="agree" type="checkbox" value="{{ old('agree') }}" required>
                      @if ($errors->has('isbn'))
                        <span class="error">
                          {{ $errors->first('isbn') }}
                        </span>
                      @endif
                      I have read and agree with the terms of service above</label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary col-md-12">Create Auction</button>
                </div>
            </div>
        </form>

    </main>
</div>
</div>
@endsection
