<?php

namespace App\Http\Controllers\User\UserGuest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

            return response()->json(['msg' => $msg], 400);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        if ($user) {
            return response()->json(['msg' => 'Registation Completed'], 200);
        } else {
            return response()->json(['msg' => 'Registation Faild'], 400);
        }
    }

    function delete($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['msg' => 'User Deleted'], 200);
        } else {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(null);
    }

    function get_data()
    {
        $all_user = User::all();
        if ($all_user) {
            return response()->json($all_user, 200);
        } else {
            return response()->json(['msg' => 'User not Found'], 200);
        }
    }

    function update_data(Request $request)
    {

        $user =  User::find($request->id);

        if (is_null($user)) {
            return response()->json(['message' => "User dosen't exists"], 404);
        } else {
            $arrayRequest = [
                "name" => $request->name,
                "email" => $request->email,
            ];

            $arrayValidate  = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            ];

            $response = Validator::make($arrayRequest, $arrayValidate);

            if ($response->fails()) {
                $msg = '';
                foreach ($response->getMessageBag()->toArray() as $item) {
                    $msg = $item;
                };

                return response()->json(['message' => $msg], 400);
            } else {

                DB::beginTransaction();

                try {

                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->save();
                    DB::commit();
                } catch (\Exception $err) {
                    DB::rollBack();
                    $user = null;
                }


                if (is_null($user)) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Internal Server Error',
                        'err_message' => $err->getMessage()
                    ], 500);
                } else {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Profile Update Successfully',
                    ], 200);
                }
            }
        }
    }
    function password_change(Request $request)
    {

        $user =  User::find($request->id);

        if (is_null($user)) {
            return response()->json(['message' => "User dosen't exists"], 404);
        } else {

            $arrayRequest = [
                "old_password" => $request->old_password,
                "password" => $request->password,
                "password_confirmation" => $request->password_confirmation,
            ];

            $arrayValidate  = [
                'old_password' => 'required',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ];

            $response = Validator::make($arrayRequest, $arrayValidate);

            if ($response->fails()) {
                $msg = '';
                foreach ($response->getMessageBag()->toArray() as $item) {
                    $msg = $item;
                };

                return response()->json(['message' => $msg], 400);
            } else {

                if (Hash::check($request->old_password, $user->password)) {
                    DB::beginTransaction();

                    try {

                        $user->password = Hash::make($request->password);
                        $user->save();
                        DB::commit();
                    } catch (\Exception $err) {
                        DB::rollBack();
                        $user = null;
                    }


                    if (is_null($user)) {
                        return response()->json([
                            'status' => 0,
                            'message' => 'Internal Server Error',
                            'err_message' => $err->getMessage()
                        ], 500);
                    } else {
                        return response()->json([
                            'status' => 0,
                            'message' => 'Password Changed',
                        ], 200);
                    }
                } else {
                    return response()->json(['message' => "Old Password Dosen't Match"], 404);
                }
            }
        }
    }
}
