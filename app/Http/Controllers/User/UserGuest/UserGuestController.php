<?php

namespace App\Http\Controllers\User\UserGuest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;

class UserGuestController extends Controller
{
    function store(Request $request)
    {
        $arrayRequest = [
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password,
            "password_confirmation" => $request->password_confirmation,
        ];

        $arrayValidate  = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $response = Validator::make($arrayRequest, $arrayValidate);

        if ($response->fails()) {
            $msg = '';
            foreach ($response->getMessageBag()->toArray() as $item) {
                $msg = $item;
            };

            return response()->json(['msg' => $msg],400);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        if ($user) {
            return response()->json(['msg' => 'Registation Completed'],200);
        } else {
            return response()->json(['msg' => 'Registation Faild'],400);
        }
    }

    function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['msg' => 'User Deleted'],200 );
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(null);
    }
}
