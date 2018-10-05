<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
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
      * Approves a termination request
      * @param Request $request
      * @return 200 if success, 403 or 500 if errors
      */
    public function terminate(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            try {
                DB::beginTransaction();
                DB::update("UPDATE users SET users_status = ? WHERE id = ?", ['terminated', $id]);
                DB::delete('DELETE FROM requested_termination WHERE idusers=?', [$id]);
                DB::commit();
            } catch (QueryException $qe) {
                DB::rollBack();
                $this->warn($qe);
                return response('NOT FOUND', 404);
            }
        } else {
            return response('Incorrect Request', 400);
        }

        return response('This user account was terminated. Account id: '.$id, 200);
    }

    /**
      * Ignores a termination request
      * @param Request $request
      * @return 200 if success, 403 or 500 if errors
      */
    public function ignore(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            try {
                DB::delete('DELETE FROM requested_termination WHERE idusers=?', [$id]);
            } catch (QueryException $qe) {
                $this->warn($qe);
                return response('NOT FOUND', 404);
            }
        } else {
            return response('Incorrect Request', 400);
        }

        return response('OK', 200);
    }

    /**
      * Suspends an user
      * @param Request $request
      * @return 200 if success, 403 or 500 if errors
      */
    public function suspend(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            try {
                DB::update("UPDATE users SET users_status = ? WHERE id = ?", ['suspended', $id]);
            } catch (QueryException $qe) {
                $this->warn($qe);
                return response('NOT FOUND', 404);
            }
        } else {
            return response('Incorrect Request', 400);
        }

        return response('This user account was suspended. Account id is: '.$id, 200);
    }

    /**
      * Promote/Revoke a user to a moderator
      * @param Request $request
      * @return 200 if success, 403 or 500 if errors
      */
    public function reactivate_or_revokeModerator(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            try {
                DB::update("UPDATE users SET users_status = ? WHERE id = ?", ['normal', $id]);
            } catch (QueryException $qe) {
                $this->warn($qe);
                return response('NOT FOUND', 404);
            }
        } else {
            return response('Incorrect Request', 400);
        }
        return response('This user account status was set to normal. Account id is: '.$id, 200);
    }

    /**
      * Bans someone
      * @param Request $request
      * @return 200 if success, 403 or 500 if errors
      */
    public function ban(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            try {
                DB::update("UPDATE users SET users_status = ? WHERE id = ?", ['banned', $id]);
            } catch (QueryException $qe) {
                $this->warn($qe);
                return response('NOT FOUND', 404);
            }
        } else {
            return response('Incorrect Request', 400);
        }

        return response('This account was banned. Account id is: '.$id, 200);
    }

    /**
      * Promotes someone to a moderator
      * @param Request $request
      * @return 200 if success, 403 or 500 if errors
      */
    public function promote_moderator(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            try {
                DB::update("UPDATE users SET users_status = ? WHERE id = ?", ['moderator', $id]);
            } catch (QueryException $qe) {
                $this->warn($qe);
                return response('NOT FOUND', 404);
            }
        } else {
            return response('Incorrect Request', 400);
        }

        return response('This user account was promoted to moderator. Account id is: '.$id, 200);
    }

    /**
      * Visits a profile
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function visit_profile(Request $request)
    {
        if (!($request->ajax() || $request->pjax()) || !Auth::Check() || Auth::user()->users_status != 'admin') {
            return response('Forbidden.', 403);
        }

        $id = $request->input('id_member');
        if ($id !== null) {
            return response($id, 200);
        }


    }

    /**
      * Does an admin action
      * @param Request $request
      * @return JSON if success, 403 or 500 if errors
      */
    public function action(Request $request)
    {
        try {
            if ($request->id_member == -1) {
                $user = User::where('username', $request->username)->limit(1)->get()->first();
                if (isset($user->id)) {
                    $request->merge(['id_member' => $user->id]);
                } else {
                    return "Error: Username doesn't exist";
                }
            }

            $action = $request->action;

            if ($action === "remove_profile") {
                return $this->terminate($request);
            }

            if ($action === "visit_profile") {
                return $this->visit_profile($request);
            }

            if ($action === "ignore_del_request") {
                return $this->ignore($request);
            }

            if ($action === "suspend") {
                return $this->suspend($request);
            }

            if ($action === "normal") {
                return $this->reactivate_or_revokeModerator($request);
            }

            if ($action === "ban") {
                return $this->ban($request);
            }

            if ($action === "promote") {
                return $this->promote_moderator($request);
            }
        } catch (Exception $e) {
            $this->error($e);
            return response('Internal Error', 500);
        }

        return $this->unkown_action($request);
    }
}
