<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Setting $setting)
    {
        $setting = $setting->first();

        $data = [
            'setting' => $setting
        ];

        return view('setting.setting', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        // yes yes ... we can do handy dandy things like create
        // a separate request etc... but doint it here as its amicro project

        // bad validation,
        $this->validate($request, [
            'php_name' => 'required|min:3|max:5',
            'docudex_path' => 'required',
            'files_path' => 'required',
            'config_path' => 'required',
        ]);

        // update setting
        $setting->first()->update($request->all());

        // redirect
        return redirect()->route('setting.index')->with('status', 'Setting saved.');
    }
}
