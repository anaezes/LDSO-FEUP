<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ProposalController;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
      * gets the advanced search page
      * @return page
      */
    public function show()
    {
        $facultys = [];
        $responseSentence = "Use the advanced search options above to find facultys";

        return view('pages.search', ['facultys' => $facultys, 'responseSentence' => $responseSentence]);
    }

    /**
      * does a simpel search request
      * @param Request $request
      * @return page with results
      */
    public function simpleSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'searchTerm' => 'nullable|string',
            'faculty' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('home')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $searchTerm = $input['searchTerm'];
        $faculty = $input['faculty'];
        $approved = "approved";
        $responseSentence = [];
        $ids = [];
        $facultys = [];

        try {
            if ($searchTerm != null) {
                $res = DB::select("SELECT faculty.id FROM faculty WHERE title @@ plainto_tsquery('english',?) and faculty_status = ?", [$searchTerm, $approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, ' with title "' . $searchTerm . '"');
            }
            if ($faculty !== 'All') {
                $res = DB::select('SELECT faculty.id FROM faculty, faculty_faculty, faculty WHERE faculty_faculty.idfaculty = faculty.id and faculty_faculty.idfaculty = faculty.id and facultyName = ? and faculty_status = ?', [$faculty, $approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, 'in faculty ' . $faculty);
            } else {
                $res = DB::select("SELECT id FROM faculty WHERE faculty_status = ?", [$approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, 'in any faculty');
            }

            if (sizeof($ids) == 0) {
                return view('pages.search', ['facultys' => [], 'responseSentence' => "No results were found"]);
            }
            $parameters = implode(",", $ids);

            $query = "SELECT faculty.id, title, author, duration, dateApproved FROM faculty WHERE faculty.id IN (" . $parameters . ")";
            $facultys = DB::select($query, []);

            $this->buildTimestamps($facultys);
            $this->getMaxBids($facultys);
            $this->getImage($facultys);

            $responseSentence = implode(' and ', $responseSentence);
            $responseSentence = 'Your search results for facultys ' . $responseSentence . ':';
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem searching for facultys. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('search')
                ->withErrors($errors);
        }

        return view('pages.search', ['facultys' => $facultys, 'responseSentence' => $responseSentence]);
    }

    /**
      * Builds all timestamps for an array of facultys
      * @param $facultys
      */
    private function buildTimestamps($facultys)
    {
        foreach ($facultys as $faculty) {
            $ts = ProposalController::createTimestamp($faculty->dateapproved, $faculty->duration);
            $faculty->timestamp = $ts;
        }
    }

    /**
      * sets the max bid on an array of facultys
      * @param $facultys
      */
    private function getMaxBids($facultys)
    {
        foreach ($facultys as $faculty) {
            $res = DB::select("SELECT max(bidValue) FROM bid WHERE idfaculty = ?", [$faculty->id]);
            if ($res[0]->max == null) {
                $faculty->bidValue = "No bids yet";
            } else {
                $faculty->bidValue = $res[0]->max . "€";
            }
        }
    }

    /**
      * sets the image on an array of facultys
      * @param $facultys
      */
    private function getImage($facultys)
    {
        foreach ($facultys as $faculty) {
            $image = DB::select("SELECT source FROM image WHERE idfaculty = ? limit 1", [$faculty->id]);
            if (isset($image[0]->source)) {
                $faculty->image = $image[0]->source;
            } else {
                $faculty->image = "book.png";
            }
        }
    }
}
