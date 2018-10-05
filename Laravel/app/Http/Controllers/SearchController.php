<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuctionController;
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
        $auctions = [];
        $responseSentence = "Use the advanced search options above to find auctions";

        return view('pages.search', ['auctions' => $auctions, 'responseSentence' => $responseSentence]);
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
            'category' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('home')
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();
        $searchTerm = $input['searchTerm'];
        $category = $input['category'];
        $approved = "approved";
        $responseSentence = [];
        $ids = [];
        $auctions = [];

        try {
            if ($searchTerm != null) {
                $res = DB::select("SELECT auction.id FROM auction WHERE title @@ plainto_tsquery('english',?) and auction_status = ?", [$searchTerm, $approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, ' with title "' . $searchTerm . '"');
            }
            if ($category !== 'All') {
                $res = DB::select('SELECT auction.id FROM auction, category_auction, category WHERE category_auction.idAuction = auction.id and category_auction.idCategory = category.id and categoryName = ? and auction_status = ?', [$category, $approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, 'in category ' . $category);
            } else {
                $res = DB::select("SELECT id FROM auction WHERE auction_status = ?", [$approved]);
                foreach ($res as $entry) {
                    array_push($ids, $entry->id);
                }

                array_push($responseSentence, 'in any category');
            }

            if (sizeof($ids) == 0) {
                return view('pages.search', ['auctions' => [], 'responseSentence' => "No results were found"]);
            }
            $parameters = implode(",", $ids);

            $query = "SELECT auction.id, title, author, duration, dateApproved FROM auction WHERE auction.id IN (" . $parameters . ")";
            $auctions = DB::select($query, []);

            $this->buildTimestamps($auctions);
            $this->getMaxBids($auctions);
            $this->getImage($auctions);

            $responseSentence = implode(' and ', $responseSentence);
            $responseSentence = 'Your search results for auctions ' . $responseSentence . ':';
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem searching for auctions. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('search')
                ->withErrors($errors);
        }

        return view('pages.search', ['auctions' => $auctions, 'responseSentence' => $responseSentence]);
    }

    /**
      * Builds all timestamps for an array of auctions
      * @param $auctions
      */
    private function buildTimestamps($auctions)
    {
        foreach ($auctions as $auction) {
            $ts = AuctionController::createTimestamp($auction->dateapproved, $auction->duration);
            $auction->timestamp = $ts;
        }
    }

    /**
      * sets the max bid on an array of auctions
      * @param $auctions
      */
    private function getMaxBids($auctions)
    {
        foreach ($auctions as $auction) {
            $res = DB::select("SELECT max(bidValue) FROM bid WHERE idAuction = ?", [$auction->id]);
            if ($res[0]->max == null) {
                $auction->bidValue = "No bids yet";
            } else {
                $auction->bidValue = $res[0]->max . "€";
            }
        }
    }

    /**
      * sets the image on an array of auctions
      * @param $auctions
      */
    private function getImage($auctions)
    {
        foreach ($auctions as $auction) {
            $image = DB::select("SELECT source FROM image WHERE idauction = ? limit 1", [$auction->id]);
            if (isset($image[0]->source)) {
                $auction->image = $image[0]->source;
            } else {
                $auction->image = "book.png";
            }
        }
    }
}
