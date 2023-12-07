<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use UnexpectedValueException;
use DomainException;
use TypeError;
use Exception;

class JwtAuth
{
	public static function getToken($data)
	{
		return JWT::encode($data, config('auth.jwt_key'), 'HS256');
	}

	public static function getDataFromToken($token)
	{
		$decoded_token = null;

		try {
			$decoded_token = JWT::decode($token, new Key(config('auth.jwt_key'), 'HS256'));
		} catch (UnexpectedValueException $error) {
			$decoded_token = null;
		} catch (DomainException $error) {
			$decoded_token = null;
		} catch (TypeError $error) {
			$decoded_token = null;
		} catch (Exception $error) {
			$decoded_token = null;
		}

		return $decoded_token;
	}
}
