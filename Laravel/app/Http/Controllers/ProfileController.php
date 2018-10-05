<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Exception;

class ProfileController extends Controller
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
     * Shows the User profile for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect('/home');
        }

        $user = User::find($id);

        $images = DB::table('image')->where('idusers', $id)->pluck('source');
        if (sizeof($images) == 0) {
            $images = ["default.png"];
        }

        $paypalMsg = "";
        if ($user->paypalemail != null) {
            $paypalMsg = "You already linked your IBAN";
        } else {
            $paypalMsg = "You aren't linked to your IBAN";
        }

        return view('pages.profile', ['user' => $user, 'image' => $images[0], 'paypalMsg' => $paypalMsg]);
    }

    /**
      * Edits an user profile
      * @param Request $request
      * @param int $id
      * @return redirect to profile
      */
    public function editUser(Request $request, $id)
    {
        if (Auth::user()->id != $id) {
            return redirect('/home');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'age' => 'nullable|min:18|integer',
            'address' => 'nullable|string|max:255',
            'idcountry' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile', ['id' => Auth::user()->id])
                ->withErrors($validator)
                ->withInput();
        }

        $input = $request->all();

        try {
            if ($input['name'] !== null) {
                DB::update('update users set name = ? where id = ?', [$input['name'], $id]);
            }

            if ($input['age'] !== null) {
                DB::update('update users set age = ? where id = ?', [$input['age'], $id]);
            }

            if ($input['email'] !== null) {
                DB::update('update users set email = ? where id = ?', [$input['email'], $id]);
            }

            if ($input['address'] !== null) {
                DB::update('update users set address = ? where id = ?', [$input['address'], $id]);
            }

            if ($input['postalcode'] !== null) {
                DB::update('update users set postalCode = ? where id = ?', [$input['postalcode'], $id]);
            }

            if ($input['idcountry'] !== null) {
                DB::update('update users set idCountry = ? where id = ?', [$input['idcountry'], $id]);
            }

            if ($input['phone'] !== null) {
                DB::update('update users set phone = ? where id = ?', [$input['phone'], $id]);
            }

            $image = $request->file('image');
            if ($image !== null) {
                $input['imagename'] = time() . $image->getClientOriginalName();
                $image->move('img', $input['imagename']);
                if (sizeof(DB::select('select * FROM image WHERE idusers = ?', [$id])) > 0) {
                    DB::update('update image set source = ? where idusers = ?', [$input['imagename'], $id]);
                } else {
                    DB::insert('INSERT INTO image (source,idusers) VALUES(?,?)', [$input['imagename'], $id]);
                }
            }
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem editing your information. Try Again!");

            $this->warn($qe);
            return redirect()
                ->route('profile', ['id' => Auth::user()->id])
                ->withErrors($errors);
        }
        return redirect()->route('profile', ['id' => Auth::user()->id]);
    }

    /**
      * adds a paypal email to the user
      * @param Request $request
      * @param int $id
      * @return redirect to page
      */
    public function addPaypal(Request $request, $id)
    {
        if (Auth::user()->id != $id) {
            return redirect('/home');
        }

        $validator = Validator::make($request->all(), [
            'paypalEmail' => 'nullable|string|email',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('profile', ['id' => Auth::user()->id])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $input = $request->all();
            $email = $input['paypalEmail'];

            DB::update('UPDATE users SET paypalEmail = ? WHERE id = ?', [$email, Auth::user()->id]);
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem adding your paypal. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('profile', ['id' => Auth::user()->id])
                ->withErrors($errors);
        }
        return redirect()->route('profile', ['id' => $id]);
    }

    /**
      * removes a paypal email of the user
      * @param Request $request
      * @param int $id
      * @return redirect to page
      */
    public function removePaypal(Request $request, $id)
    {
        if (Auth::user()->id != $id) {
            return redirect('/home');
        }
        try {
            $paypalEmail = "NULL";

            DB::update('UPDATE user SET paypalEmail = ? WHERE id = ?', [$paypalEmail, $id]);
        } catch (QueryException $qe) {
            $errors = new MessageBag();

            $errors->add('An error ocurred', "There was a problem removing your paypal information. Try Again!");
            $this->warn($qe);
            return redirect()
                ->route('profile', ['id' => Auth::user()->id])
                ->withErrors($errors);
        }
        return redirect()->route('profile', ['id' => $id]);
    }
}
