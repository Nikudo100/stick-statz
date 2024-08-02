<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Services\Management\ClusterManager;
use App\Models\Region;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index(Request $request, ClusterManager $clusterManager)
    {
        $account = $request->user()->account;
        $setting = $account->setting;

        if (!$setting) {
            $setting = $account->setting()->create();
        }

        $clusters = $clusterManager->getAllClusters();
        $warehouses = Warehouse::where('cluster_id', null)->get();
        $regions = Region::where('cluster_id', null)->get();

        return view('settings.index', compact('setting', 'clusters', 'warehouses', 'regions'));
    }

    public function updateClusters(Request $request, ClusterManager $clusterManager)
    {
        $data = $request->validate([
            'cluster_id' => 'nullable|integer|exists:clusters,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'sort' => 'required|integer',
            'warehouse_ids' => 'array',
            'warehouse_ids.*' => 'integer|exists:warehouses,id',
            'order_region_ids' => 'array',
            'order_region_ids.*' => 'integer|exists:regions,id',
        ]);

        Log::info('Received data for updating clusters', $data);

        $clusterManager->saveCluster($data);

        return redirect()->route('settings.index')->with('success', 'Кластер обновлен успешно.');
    }

    public function removeRegionsAndWarehouses(Request $request, ClusterManager $clusterManager)
    {
        $data = $request->validate([
            'cluster_id' => 'required|integer|exists:clusters,id',
            'remove_warehouse_ids' => 'array',
            'remove_warehouse_ids.*' => 'integer|exists:warehouses,id',
            'remove_order_region_ids' => 'array',
            'remove_order_region_ids.*' => 'integer|exists:regions,id',
        ]);

        Log::info('Received data for removing regions and warehouses', $data);

        $clusterManager->removeRegionsAndWarehouses($data);

        return redirect()->route('settings.index')->with('success', 'Регионы и склады успешно удалены.');
    }

    function update(SettingRequest $request)
    {
        $validated = $request->validated();
        $validated['auto_feedback_answer'] = isset($validated['auto_feedback_answer']) ? $validated['auto_feedback_answer'] : false;
        $account = $request->user()->account;
        $setting = $account->setting;

        if (!$setting) {
            $setting = $account->setting()->create($validated);
        } else {
            $setting->update($validated);
        }

        return redirect()->back();
    }


    public function deleteCluster($id, ClusterManager $clusterManager)
    {
        $clusterManager->deleteCluster($id);

        return redirect()->route('settings.index')->with('success', 'Кластер удален успешно.');
    }
}
