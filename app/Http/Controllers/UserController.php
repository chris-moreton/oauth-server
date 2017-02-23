<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(['error' => 'expected json payload'], Response::HTTP_BAD_REQUEST);
        }
        
        $user = new User;
        
        foreach ($request->json() as $key => $value) {
            if (in_array($key, $user->getFillable())) {
                if ($key == 'password') {
                    $value = \Hash::make($value);
                }
                $user->$key = $value;
            } else {
                return response()->json(['error' => 'invalid field: ' . $key], Response::HTTP_BAD_REQUEST);
            }
        }
        
        $user->save();
        
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (str_contains($id, '@')) {
            $key = 'email';
        } else {
            $key = 'id';
        }
        
        return User::where($key, $id)->first();
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        if (!$request->isJson()) {
            return response()->json(['error' => 'expected json payload'], Response::HTTP_BAD_REQUEST);
        }
        
        $user = User::where('id', $id)->first();

        if ($user) {
            $count = 0;
            foreach ($request->json() as $key => $value) {
                if (in_array($key, $user->getFillable())) {
                    if ($key == 'password') {
                        $value = \Hash::make($password);
                    }
                    $user->$key = $value;
                } else {
                    return response()->json(['error' => 'invalid field: ' . $key], Response::HTTP_BAD_REQUEST);
                }
            }
            $user->save();
            return $user;
        }
        
        return response()->json(['error' => 'user not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Verify the user's password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function passwordcheck(Request $request, $email)
    {
        $password = $request->get('password');

        $user = User::where('email', $email)->first();
        
        $verified = false;
        
        if ($user) {
            $verified = \Hash::check($password, $user->password);
        }
        
        return ['verified' => $verified];
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
