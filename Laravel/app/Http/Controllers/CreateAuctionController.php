<?php

namespace App\Http\Controllers;

use App\Auction;
use App\Category;
use App\CategoryAuction;
use App\Http\Controllers\Controller;
use App\Image;
use App\Language;
use App\Publisher;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class CreateAuctionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function show()
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        return view('pages.create');
    }

    /**
      * Creates a new auction
      * @param Request $request
      * @return created auction
      */
    private function db_create(Request $request)
    {
        $createdAuction = DB::transaction(function () use ($request) {
            $saveAuction = new Auction;
            $saveCategoryAuction = new CategoryAuction;
            $saveAuction->idseller = Auth::user()->id;

            $savePublisher = Publisher::where('publishername', $request->input('publisher'))->get()->first();

            if ($savePublisher == null) {
                $savePublisher = new Publisher;
                $savePublisher->publishername = $request->input('publisher');
                $savePublisher->save();
                $savePublisher = $savePublisher->id;
            } else {
                $savePublisher = $savePublisher->id;
            }

            $saveCategory = Category::where('categoryname', $request->input('category'))->get()->first();

            $saveAuction->idpublisher = $savePublisher;
            $saveAuction->idlanguage = Language::where('languagename', $request->input('language'))->get()->first()->id;

            $saveAuction->title = $request->input('title');
            $saveAuction->author = $request->input('author');
            $saveAuction->description = $request->input('description');
            $saveAuction->isbn = $request->input('isbn');
            $saveAuction->duration = $this->buildDuration($request);

            $saveAuction->save();

            if ($saveCategory != null) {
                $saveCategoryAuction->idcategory = $saveCategory->id;
                $saveCategoryAuction->idauction = $saveAuction->id;
                $saveCategoryAuction->save();
            }

            $input = $request->all();
            $images = array();
            if ($files = $request->file('images')) {
                $integer = 0;
                foreach ($files as $file) {
                    $name = time() . (string) $integer . $file->getClientOriginalName();
                    $file->move('img', $name);
                    $images[] = $name;
                    $integer += 1;
                }
            }

            foreach ($images as $image) {
                $saveImage = new Image;
                $saveImage->source = $image;
                $saveImage->idauction = $saveAuction->id;
                $saveImage->save();
            }

            return $saveAuction;
        });

        return $createdAuction;
    }

    /**
     * Creates a new auction and redirects to it's page.
     *
     */
    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        try {
            $createdAuction = $this->db_create($request);
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem creating the auction. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('create')
                ->withErrors($errors);
        }
        return redirect()->route('auction', ['id' => $createdAuction->id]);
    }

    private function buildDuration(Request $request)
    {
        $days = $request->input('days');
        $hours = $request->input('hours');
        $minutes = $request->input('minutes');

        $totalSeconds = $days * 86400 + $hours * 3600 + $minutes * 60;
        return $totalSeconds;
    }
}
