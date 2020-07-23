<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
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
        $this->middleware('api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
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
    public function show($id)
    {
        //
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
        $user=User::findOrFail($id);
        $user->delete();
        return ['msg'=>'User Deleted'];

    }
}
