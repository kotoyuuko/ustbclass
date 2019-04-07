<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();

        if ($data['email'] != $user->email) {
            $data['email_verified_at'] = null;
        }

        if (isset($data['receive_email']) && $data['receive_email'] == 'true') {
            $data['receive_email'] = true;
        } else {
            $data['receive_email'] = false;
        }

        $user->update($data);

        return redirect()
            ->route('users.edit', $user->id)
            ->with('success', '个人资料更新成功！');
    }
}
