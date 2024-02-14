<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|min:10'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            $user = new User();
            $user->phone = $request->phone;
            $user->save();

            if (!$user) {
                return $this->response()->json(['message' => 'Could not proccess a user with that phone number'], 401);
            }
        }

        $user->notify(new LoginNeedsVerification());

        return $this->response()->json(['message' => 'Text message notification sent']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric'
        ]);

        $user = User::where('phone', $request->phone)->where('login_code', $request->login_code);

        if ($user) {
            $user->update([
                'login_code' => null
            ]);

            return $user->createToken($request->login_code)->plainTextToken;
        }

        return $this->response()->json(['message' => 'Login code is invalid'], 401);
    }
}
