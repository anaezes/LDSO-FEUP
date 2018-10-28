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


        $skills = DB::select("SELECT skillName FROM skill,skill_user INNER JOIN users ON users.id = skill_user.idSkill WHERE skill_user.idSkill=skill.id AND skill_user.idUser=users.id");

        $user->skills=$skills;

       //dd($skills);


        $images = DB::table('image')->where('idusers', $id)->pluck('source');
        if (sizeof($images) == 0) {
            $images = ["default.png"];
        }


        return view('pages.profile', ['user' => $user, 'image' => $images[0]]);
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
            'idfaculty' => 'nullable|integer',
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

            if ($input['email'] !== null) {
                DB::update('update users set email = ? where id = ?', [$input['email'], $id]);
            }

            if ($input['idfaculty'] !== null) {
                DB::update('update users set idfaculty = ? where id = ?', [$input['idfaculty'], $id]);
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


}
