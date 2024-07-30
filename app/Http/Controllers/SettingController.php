<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    function index(Request $request)
    {
        $account = $request->user()->account;
        $setting = $account->setting;

        if (!$setting)
            $setting = $account->setting()->create();

        return view('settings.index', compact('setting'));
    }
}
