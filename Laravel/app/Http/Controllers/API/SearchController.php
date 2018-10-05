<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BidController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
      * Does an advanced search
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function search(Request $request)
    {
        try {
            if (!($request->ajax() || $request->pjax())) {
                return response('Forbidden.', 403);
            }

            $queryResults = [];

            if ($request->input('title') != null) {
                $res = DB::select("SELECT id FROM auction WHERE title @@ plainto_tsquery('english',?)", [$request->input('title')]);
                array_push($queryResults, $res);
            }
            if ($request->input('author') != null) {
                $res = DB::select("SELECT id FROM auction WHERE author = ?", [$request->input('author')]);
                array_push($queryResults, $res);
            }
            if ($request->input('isbn') != null) {
                $res = DB::select("SELECT id FROM auction WHERE isbn = ?", [$request->input('isbn')]);
                array_push($queryResults, $res);
            }
            if ($request->input('auctionStatus') != null) {
                $res = DB::select("SELECT id FROM auction WHERE auction_status = ?", [$request->input('auctionStatus')]);
                array_push($queryResults, $res);
            }
            if ($request->input('wishlistOfUser') !== null && Auth::check()) {
                $res = DB::select("SELECT DISTINCT auction.id FROM auction, whishlist WHERE whishlist.idAuction = auction.id and whishlist.idBuyer = ? AND auction_status=?", [Auth::user()->id, 'approved']);
                array_push($queryResults, $res);
            }

            if ($request->input('history') !== null && Auth::check()) {
                $res = DB::select("SELECT DISTINCT auction.id FROM auction, bid WHERE bid.idAuction = auction.id and bid.idBuyer = ? AND auction_status = ?", [Auth::user()->id, 'finished']);
                $res1 = DB::select("SELECT DISTINCT auction.id FROM auction WHERE idSeller = ? AND auction_status = ?", [Auth::user()->id, 'finished']);
                array_push($queryResults, $res);
                array_push($queryResults, $res1);
            }
            if ($request->input('auctionsOfUser') !== null && Auth::check()) {
                $res = DB::select("SELECT DISTINCT auction.id FROM auction WHERE idSeller = ? AND (auction_status = ? OR auction_status=?)", [Auth::user()->id, 'approved', 'waitingApproval']);
                array_push($queryResults, $res);
            }
            if ($request->input('userBidOn') !== null && Auth::check()) {
                $res = DB::select("SELECT DISTINCT auction.id FROM auction, bid WHERE bid.idAuction = auction.id and bid.idBuyer = ? and auction.auction_status = ? ", [Auth::user()->id, 'approved']);
                array_push($queryResults, $res);
            }
            if ($request->input('language') != null) {
                $res = DB::select("SELECT DISTINCT auction.id FROM auction, language WHERE auction.idLanguage = language.id and language.languageName = ?", [$request->input('language')]);
                array_push($queryResults, $res);
            }
            if ($request->input('publisher') != null) {
                $res = DB::select("SELECT DISTINCT auction.id FROM auction, publisher WHERE auction.idPublisher = publisher.id and publisher.publisherName = ?", [$request->input('publisher')]);
                array_push($queryResults, $res);
            }

            $counts = [];
            foreach ($queryResults as $res) {
                foreach ($res as $id) {
                    if (!array_key_exists($id->id, $counts)) {
                        $counts[$id->id] = 1;
                    } else {
                        $counts[$id->id]++;
                    }
                }
            }

            arsort($counts);
            $counts = array_unique(array_keys($counts));

            $ids = implode(",", array_values($counts));
            if ($ids === "") {
                $ids = "-1";
            }
            $query = "SELECT auction.id, title, author, duration, dateApproved, auction_status FROM auction WHERE auction.id IN (" . $ids . ")";
            $response = DB::select($query, []);

            foreach ($response as $auction) {
                $auction->maxBid = BidController::getMaxBidInternal($auction->id);
                if ($auction->maxBid == 0) {
                    $auction->bidMsg = "No bids yet";
                } else {
                    $auction->bidMsg = $auction->maxBid . "€";
                }

                if ($auction->auction_status == "waitingApproval") {
                    $auction->time = "Not yet started";
                } elseif ($auction->auction_status == "approved") {
                    $auction->time = AuctionController::createTimestamp($auction->dateapproved, $auction->duration);
                } elseif ($auction->auction_status == "finished") {
                    $auction->time = "Finished";
                }

                $image = DB::select("SELECT source FROM image WHERE idauction = ? limit 1", [$auction->id]);
                if (isset($image[0]->source)) {
                    $auction->image = $image[0]->source;
                } else {
                    $auction->image = "book.png";
                }

                if (Auth::check()) {
                    $wish = DB::select("SELECT * FROM whishlist WHERE idbuyer = ? and idauction = ?", [Auth::user()->id, $auction->id]);
                    if (sizeof($wish) > 0) {
                        $auction->wishlisted = '<i class="fas fa-star wishlist btn btn-sm text-primary"></i>';
                    } else {
                        $auction->wishlisted = '<i class="far fa-star wishlist btn btn-sm text-primary"></i>';
                    }
                } else {
                    $auction->wishlisted = '<i class="far fa-star wishlist btn btn-sm text-primary"></i>';
                }
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return response()->json($response);
    }
}
