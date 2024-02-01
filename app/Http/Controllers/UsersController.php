<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\PermissionsModel;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        # code...
        $this->middleware(['permission:users-list|users-create|users-edit|users-delete']);
    }

    public function index(UsersDataTable $dataTable)
    {
        $title = 'Users LHP Batanghari';
        $judul = 'Data Users';
        return $dataTable->render('pages.users.index', compact('title', 'judul'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Data Users';
        $judul = 'Tambah Data Users';
        $role = Role::get();
        $wilayah = Wilayah::get();

        return view('pages.users.add', compact('title', 'judul', 'role', 'wilayah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        # code...
        $rules = [
            'name' => 'required|min:3',
            'username'  => 'required|min:3',
            'email'     => 'required|email|unique:users',
            'password'      => 'required|min:3',
        ];

        $messages = [
            'name.required'  => 'Nama Lengkap wajib diisi',
            'name.min'       => 'Nama Lengkap minimal 3 karakter',
            'name.required'  => 'Username Wajib diisi',
            'name.min'       => 'Username minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.unique'      => 'Email Sudah Terdaftar, Coba Email yang Lain',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 3 karakter',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = Role::find($request->role);
        $user = User::create([
            'wilayah_id' => $request->wilayah,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'active' => ($role->name == 'Irban') ? 1 : 99,

        ]);

        $user->assignRole($role->name);
        return redirect()->route('users')
            ->with('success', 'User Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Data Users';
        $judul = 'Edit Data Users';
        $user = User::find($id);
        $wilayah = Wilayah::get();

        return view('pages.users.edit', compact('title', 'judul',  'user', 'wilayah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:3',
            'username'  => 'required|min:3',
            'email'     => 'required|email',
        ];

        $messages = [
            'name.required'  => 'Nama wajib diisi',
            'name.min'       => 'Nama minimal 3 karakter',
            'username.required'  => 'Username wajib diisi',
            'username.min'       => 'Username minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        $pass = $user->password;

        if (empty($request->password)) {
            $pass;
        } else {
            $pass = Hash::make($request->password);
        }

        $user->update([
            'wilayah_id' => $request->wilayah,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $pass,
        ]);
        return redirect()->route('users')
            ->with('success', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users')
            ->with('error', 'User Deleted Successfully');
    }
}
