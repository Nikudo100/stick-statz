<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Services\Management\ClusterManager;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    function index(Request $request,ClusterManager $clusterManager)
    {
        $account = $request->user()->account;
        $setting = $account->setting;

        if (!$setting)
            $setting = $account->setting()->create();


        // $clusterManager->getAllClusters();
        // dd($clusterManager->getAllClusters());

        return view('settings.index', compact('setting'));
    }

    function update(SettingRequest $request)
    {
        $validated = $request->validated();
        $validated['auto_feedback_answer'] = isset($validated['auto_feedback_answer']) ? $validated['auto_feedback_answer'] : false;
        $account = $request->user()->account;
        $setting = $account->setting;

        if (!$setting)
            $setting = $account->setting()->create($validated);
        else
            $setting->update($validated);

        return redirect()->back();
    }
}
