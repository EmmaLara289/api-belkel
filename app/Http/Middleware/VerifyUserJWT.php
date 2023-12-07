<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use JwtAuth;
use Illuminate\Support\Facades\DB;

class VerifyUserJWT {
    public function handle(Request $request, Closure $next)
    {
        $decoded_token = JwtAuth::getDataFromToken($request->header('Authorization'));

        if ($decoded_token == null || !isset($decoded_token->id)) {
            return response()->json([
                'token' => 'The received token in the [Authorization] header is wrong or has expired'
            ], 401);
        }

        $user = DB::table('users')
            ->where('id_user', '=', $decoded_token->id)
            ->first();

        if (!is_object($user)) {
            return response()->json([
                'token' => 'The user owner of the received token in the [Authorization] header does not exist.'
            ], 401);
        }

        if ($user->status != 1) {
            return response()->json([
                'token' => 'The user is disabled.'
            ], 401);
        }

        $request->merge(['decoded_token' => $decoded_token]);
        return $next($request);
    }
}
