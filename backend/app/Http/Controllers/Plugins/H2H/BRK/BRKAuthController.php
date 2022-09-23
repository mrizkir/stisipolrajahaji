<?php

namespace App\Http\Controllers\Plugins\H2H\BRK;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\System\ConfigurationModel;

use App\Helpers\Helper;
use Exception;

class BRKAuthController extends Controller
{
	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request)
	{
		$response = trim(str_replace("\n", "", $request->getContent()));		
		
		try 
		{

			if (!Helper::isJson($response)) {
				throw new Exception (10);
			}
			
			$data_user = json_decode($response);			
			$username = $data_user->username;
			$password = $data_user->password;

			$result = User::where('username', $username)
				->where('page', 'api')
				->first();
			
			if (is_null($result)) 
			{
				throw new Exception (11);
			}
			
			$pass = hash('sha256', $result->salt . hash('sha256', $password));

			if ($result->userpassword == $pass && $result->active == 1) {
				$token = $this->guard()->setTTL(1440)->login($result);
				// ConfigurationModel::toCache();  
				return response()->json([
					'Result' => [
						'status'=>'00',
						'token'=>$token,
						'message'=>'Request berhasil'
					]
				], 200); 
				return $this->respondWithToken($token);
			}
			else
			{
				throw new Exception (12);
			}

		}
		catch(Exception $e)
		{
			$code = $e->getMessage();
			switch($code)
			{
				case 10:
					$result = [
						'status'=>'10',
						'message'=>'Format JSON tidak valid'
					];
				break;
				case 11:
					$result = [
						'status'=>'11',
						'message'=>'Username atau Password salah'
					];
				break;
				case 12:
					$result = [
						'status'=>'11',
						'message'=>"User $username sudah tidak aktif"
					];
				break;
				default:
					$result = [
						'status'=>'11',
						'message'=>$e->getMessage()
					];
			}

			return response()->json([
				'Result' => $result
			], 200);
		}

		// $credentials = $request->only('username', 'password');
		// $credentials['active']=1;

		// if (! $token = $this->guard()->attempt($credentials, ['exp' => \Carbon\Carbon::now()->addDays(1)->timestamp])) {
		// 	$result=[
		// 		'status'=>'11',
		// 		'message'=>'Username atau Password salah'
		// 	];
		// 	return response()->json([
		// 		'Result' => $result
		// 	], 200);
		// }
		// //log user loggin
		// \App\Models\System\ActivityLog::log($request,[
		// 	'object' => $this->guard()->user(), 
		// 	'object_id'=>$this->getUserid(), 
		// 	'user_id' => $this->getUserid(), 
		// 	'message' => 'user '.$credentials['username'].' berhasil login'
		// ]);

		// ConfigurationModel::toCache();  
		// return response()->json([
		// 		'Result' => [
		// 		'status'=>'00',
		// 		'token'=>$token,
		// 		'message'=>'Request berhasil'
		// 	]
		// ], 200);   
	}
	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me()
	{
		$user = $this->guard()->user();  
		if (is_null($user))
		{
			$result=[
				'status'=>'98',
				'message'=>'Token tidak terdaftar'
			];
			return response()->json([
				'Result'=>$result
			]);
		}
		else
		{
			return response()->json($user->toArray());
		}         
	}
	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token)
	{
		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => $this->guard()->factory()->getTTL() * 1440
		]);
	}
}