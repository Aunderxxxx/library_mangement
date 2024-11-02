<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class AuthController extends Controller
{
    public function role()
    {
        $roles = Role::get();
        return ApiResponse::response(200, 'Register success', RoleResource::collection($roles));
    }

    public function register(UserRegisterRequest $request)
    {
        try{
            DB::beginTransaction();

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->save();

            if($user->role_id == 1){
                $token = $user->createToken('user-register', ['role:librarian'])->plainTextToken;
            }

            if($user->role_id == 2){
                $token = $user->createToken('user-register', ['role:member'])->plainTextToken;
            }

            DB::commit();

            return ApiResponse::response(200, 'Register success', new UserResource($user), $token);

        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponse::response(400, $e->getMessage());
        }
    }

    public function login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {

            if($user->role_id == 1){
                $token = $user->createToken('user-register', ['role:librarian'])->plainTextToken;
            }

            if($user->role_id == 2){
                $token = $user->createToken('user-register', ['role:member'])->plainTextToken;
            }

            return ApiResponse::response(200, 'Login success', new UserResource($user), $token);
        }
        return ApiResponse::response(400, 'Your email or password is incorrect');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ApiResponse::response(200, 'Logout success');
    }
}
