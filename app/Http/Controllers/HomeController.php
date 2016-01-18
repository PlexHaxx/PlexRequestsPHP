<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cache;
use Validator;
use Flashy;
use App\Helpers\Helper;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Make sure we have a good Plex token

        if ( ! Cache::has('plex_token')) {
            $client = new GuzzleHttp\Client();
            $res = $client->request('POST', 'https://plex.tv/users/sign_in.json', [
                'form_params' => [
                    'user[login]' => env('PLEX_ADMIN_USERNAME'),
                    'user[password]' => env('PLEX_ADMIN_PASSWORD'),
                ],
                'headers' => [
                    'X-Plex-Product' => 'PlexRequestsPHP',
                    'X-Plex-Client-Identifier' => 'github.com/olipayne/PlexRequestsPHP',
                ],
                'http_errors' => false,
            ]);

            $body = json_decode($res->getBody());

            if ($res->getStatusCode() !== 201) {
                Flashy::error('Unable to connect to Plex Server, please check your Plex credentials');
            } else {
                Flashy::success('Plex successfully authenticated');
                Cache::forever('plex_token', $body->user->authentication_token);
            }
        }

        return view('index');
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
        $validator = Validator::make($request->all(), [
            'plex_username' => 'required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                Flashy::error($error);
            }
            return redirect('/');
        }

        // We've got a user, lets see if it's valid
        $users = Helper::getAllPlexFriends();
        if ( ! in_array($request->plex_username, $users)) {
            Flashy::error('Username not found');
            return redirect('/');
        } else {
            // Set a simple session to remember this user
            $request->session()->put('user', $request->plex_username);
            Flashy::success('Welcome, ' . $request->plex_username . '!');
            return redirect('/');
        }
    }

    /**
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
        //
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
