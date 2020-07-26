<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->authorize('isAdmin');
        if (\Gate::allows('isAdmin') || \Gate::allows('isAuthor')) {
            // The current user can edit settings
            return User::latest()->paginate(4);

        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:55',
            'email' => 'required|string|email|unique:users|max:55',
            'password' => 'required|string|min:6',

        ]);


        $form_data = array(
            'name'        =>  $request->name,
            'email'         =>  $request->email,
            'password'       =>  Hash::make($request->password),
            'type'         =>  $request->type,
            'bio'         =>  $request->bio
        );
        $user=User::create($form_data);


    }

    /**photo
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if ($search=\Request::get('q')) {
            $users = User::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%")
                        ->orWhere('bio','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $users = User::latest()->paginate(5);
        }
        return $users;
    }
    public function profile()
    {
        return auth('api')->user();
    }
    public function updateProfie(Request $request){
     $user= auth('api')->user();
     $this->validate($request,[
        'name' => 'required|string|max:191',
        'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
        'password' => 'sometimes|required|min:6'
    ]);


    $currentPhoto = $user->photo;


    if($request->photo != $currentPhoto){
        $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

        Image::make($request->photo)->save(public_path('img/profile/').$name);
        $request->merge(['photo' => $name]);

        $userPhoto = public_path('img/profile/').$currentPhoto;
        if(file_exists($userPhoto)){
            @unlink($userPhoto);
        }

    }


    if(!empty($request->password)){
        $request->merge(['password' => Hash::make($request['password'])]);
    }


    $user->update($request->all());
    return ['message' => "Success"];

    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user=User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:55',
            'email' => 'required|string|max:55|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|min:6',

        ]);
        $user->update($request->all());
        return ['msg'=>'updated user info'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $user=User::findOrFail($id);
        $user->delete();
        return ['msg'=>'User Deleted'];

    }
}
