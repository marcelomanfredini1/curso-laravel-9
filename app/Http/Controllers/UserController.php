<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdateUserFormRequest;
use App\Models\User;
use Illuminate\Http\Request;


use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //uma das formas de filtrar o search da consulta

        //$users = User::where('email', "{$request->search}")->get();
        /*if ($request->search) {
            $users = User::where('name', 'LIKE', "%{$request->search}%")->get();
            $users = User::where('email', "{$request->search}")->get();

            return view('users.index', compact('users'));
        }*/



        //outra forma de filtrar o search da consulta
        $search = $request->search;
        $users = User::where(function ($query) use ($search) {
            if ($search) {
                $query->where('email', $search);
                $query->where('name', 'LIKE', "%{$search}%");
            }
        })->get();
        #dd($users->name, $users);

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        //Uma forma de recuperar dados usando o id ou outros parametros
        //$user = User::where('id', $id)->first();
        //Outra forma para recuperar os dados 
        if (!$user = User::find($id))
            //return redirect()->back();
            return redirect()->route('users.index');

        return view('users.show', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUpdateUserFormRequest $request)
    {

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        User::create($data);

        return redirect()->route('users.index');
        /*$user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();*/
        /* dd($request->only([
            'name', 'email', 'password'
        ]));*/
    }

    public function edit($id)
    {
        if (!$user = User::find($id))
            //return redirect()->back();
            return redirect()->route('users.index');

        return view('users.edit', compact('user'));
    }

    public function update(StoreUpdateUserFormRequest $request, $id)
    {
        if (!$user = User::find($id))

            return redirect()->route('users.index');

        //return view('users.edit', compact('user'));
        $data = $request->only('name', 'email');

        if ($request->password)
            $data['password'] = bcrypt($request->password);

        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        if (!$user = User::find($id))
            //return redirect()->back();
            return redirect()->route('users.index');
        $user->delete();

        return redirect()->route('users.index');
    }
}
