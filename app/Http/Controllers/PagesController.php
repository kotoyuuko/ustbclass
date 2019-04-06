<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root()
    {
        $user = \Auth::user();

        if (!$user->elearning_id || !$user->elearning_pwd) {
            return redirect()
                ->route('users.edit', $user->id)
                ->with('warning', '请先设置学号和教务系统密码！');
        }

        return view('pages.root');
    }

    public function help()
    {
        return view('pages.help');
    }
}
