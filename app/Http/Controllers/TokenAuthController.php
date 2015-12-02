<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use Swagger\Annotations as SWG;


/**
 * @SWG\Resource(
 *  apiVersion="1.0",
 *  resourcePath="/authenticate",
 *  description="AppointmentRequest",
 *  produces="['application/json']"
 * )
 */
class TokenAuthController extends Controller
{

    /**
     * @SWG\Api(
     *  path="/authenticate",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Creates a new auth token",
     *          nickname="HTTP POST authenticate",
     *      @SWG\Parameter(
     *          name="email",
     *          description="Existing user email",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="password",
     *          description="Existing user password",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        $logged_user = Auth::user();
        return compact('token', 'logged_user');
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return compact('user');
    }

    /**
     * @SWG\Api(
     *  path="/register",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Creates a new user",
     *          nickname="HTTP POST register",
     *      @SWG\Parameter(
     *          name="id_card",
     *          description="id number",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="first_name",
     *          description="first name",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="last_name",
     *          description="last name",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="email",
     *          description="Existing user email",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="password",
     *          description="Existing user password",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *      @SWG\Parameter(
     *          name="birthday",
     *          description="user's birthday in format Y-m-d",
     *          paramType="form",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function register(UserRequest $request)
    {
        $newuser  = $request->all();
        $password = Hash::make($request->password);
        $newuser['password'] = $password;
        return User::create($newuser);
    }

        /**
     * @SWG\Api(
     *  path="/logout",
     *      @SWG\Operation(
     *          method="POST",
     *          summary="Destroy the token given",
     *          nickname="HTTP POST logout",
     *      @SWG\Parameter(
     *          name="token",
     *          description="Auth token",
     *          paramType="query",
     *              required=true,
     *              allowMultiple=false,
     *              type="string"
     *          ),
     *  )
     * )
     */
    public function logout()
    {
        $success = JWTAuth::invalidate(JWTAuth::getToken());
        return compact('success');
    }
}
