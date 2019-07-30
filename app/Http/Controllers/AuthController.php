<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Transformers\Json;

class AuthController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // $this->middleware('auth:api');
  }

  //
  public function authenticate(Request $request){
    $this->validate($request, [
        'email' => 'required|email|exists:users,email',
        'password' => 'required'
    ]);

    try {
      $user = User::where('email', $request->email)->firstOrFail();

      if(Hash::check($request->input('password'), $user->password)){
        $apiKey = base64_encode(str_random(40));
        $user->auth_key = $apiKey;
        if($user->save()){
          return response()->json(['status'=>true, 'key'=>$apiKey]);
        }
      } else {
        return Json::exception('Unauthorized', null, 401);
      }
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
      return Json::exception('Email Not Found', env('APP_ENV', 'local') == 'local' ? $e : null, 401);
    } catch (\Exception $e) {
      return Json::exception($e->getMessage(), env('APP_ENV', 'local') == 'local' ? $e : null, 401);
    }
    
  }

  public function register(Request $request){
    $data = $this->validate($request, [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'email' => 'required|unique:users',
        'phone' => [
          'required',
          "regex:/(\+62 ((\d{3}([ -]\d{3,})([- ]\d{4,})?)|(\d+)))|(\(\d+\) \d+)|\d{3}( \d+)+|(\d+[ -]\d+)|\d+/i",
          "min:10"
        ],
        'date_of_birth' => 'nullable|date',
        'sex' => 'nullable',
    ]);
    
    try {
      $user = new User;
      $user->first_name = $data['first_name'];
      $user->last_name = $data['last_name'];
      $user->email = $data['email'];
      $user->phone = $data['phone'];
      $user->date_of_birth = $request->input('date_of_birth');
      $user->sex = $request->input('sex');

      if($user->save()){
        return Json::response($user);
      } else {
        return Json::exception('Failed to Register');
      }
    } catch (\Exception $e) {
        return Json::exception($e->getMessage(), env('APP_ENV', 'local') == 'local' ? $e : null, 401);
    }
  }
}
