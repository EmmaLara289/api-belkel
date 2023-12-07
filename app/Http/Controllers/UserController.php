<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Banner;
use App\Models\Main;
use Illuminate\Support\Facades\DB;
use Validator;
use \JwtAuth;
use Illuminate\Support\Facades\Storage;
use \File;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $response = [];
        $status_code = 400;

        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required|string|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'telefono' => 'required|string|min:9',
        ]);

        if ($validator->fails()) {
            $response = $validator->errors();
        } else {

            User::create([
                'name' => $request->name,
                'email'=> $request->email,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'status' => 1, // 1 = enabled | 2 = disabled
                'telefono' => $request->telefono,
                'profile_pic' => ""
                //1 = Admin
                //2 = Colaborador
                //3 = Coordinador
            ]);

            
            $response['message'] = 'Success';
            $status_code = 200;
        }

        return response()->json($response, $status_code);
    }

    public function login(Request $request)
    {
        $response = [];
        $status_code = 400;

        $validator = Validator::make($request->all(), $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            $response = $validator->errors();
        } else {
            $user = User::where('email', 'LIKE', $request->email)->first();
        
            if ($user->status != 1) {
                $response['status'] = 'The user at the moment is disabled';
            } elseif (!password_verify($request->password, $user->password)) {
                $response['password'] = 'The password does not match';
            } else {

                $user = DB::table('users')
                ->where('id_user', '=', $user->id_user)
                ->select('id_user', 'name', 'status', 'created_at', 'telefono')
                ->first();

                //return $user;
                $jwtAuth = new JwtAuth();

                //return $key = config('auth.jwt_key');
                //dd($key); // Imprime y detiene la ejecución

                try {
                    $response = [
                        'user' => $user,
                        'token' => $jwtAuth->getToken([
                            'id' => $user->id_user,
                            'iat' => time(),
                            'exp' => time() + 43200
                        ])
                    ];
        
                    $status_code = 200;
                } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                    $response['error'] = 'Error al generar el token';
                }
            }
        }

        return response()->json($response, $status_code);
    }

    public function updateUser(Request $request){
        $status_code = 400;
        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'id_user' => 'required|integer|exists:users,id_user',
            'name' => 'required|string|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'telefono' => 'required|string|min:9',
        ]);

        if ($validator->fails()) {
            $response = $validator->errors();
        } else {

            DB::table('users')
            ->where('id_user', '=', $request->id_user)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_DEFAULT),
                'telefono' => $request->telefono
            ]);

            $status_code = 200;
            $response ['Message'] = "Success";

        }

            return response()->json($response, $status_code);
    }

    public function getUsers(Request $request){

        return response()->json(
            DB::table('users')
            ->get()
            ,
            200
        );
    }

    public function createBanner(Request $request){
        $response = [];
        $status_code = 400;

        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required|string|min:5',
            'subtitle' => 'required|string|min:5',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            $response = $validator->errors();
        } else {

            $banner = Banner::create([
                'name' => $request->name,
                'subtitle' => $request->subtitle,
                'image' => ""
            ]);

            $name = $banner->id_banner . md5(($request->image)->getClientOriginalName()) . '.' . ($request->image)->getClientOriginalExtension();
            $ruta = 'banners';
			$save = Storage::disk('public')->put($ruta .'/' . $name, File::get($request->image));
			$url = asset('storage/' . $ruta . '/' . $name);

            DB::table('banners')
            ->where('id_banner', '=', $banner->id_banner)
            ->update([
                'image' => $name
            ]);

            $response ['Message'] = $url;
            $status_code = 200;

        }

        return response()->json($response, $status_code);

    }

    public function createMain(Request $request){
        $response = [];
        $status_code = 400;

        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            $response = $validator->errors();
        } else {

            Main::create([
                'name' => $request->name,
                'status' => 1
            ]);

            $response ['Message'] = "Success";
            $status_code = 200;

        }

        return response()->json($response, $status_code);
    }

    public function getMain(Request $request){
        return response()->json(
            DB::table('main')
            ->get(),
            200
        );
    }

    public function getBanner(Request $request){
        return response()->json(
            DB::table('banners')
            ->get(),
            200
        );
    }

}
