<?php

namespace App\Http\Controllers;

use App\Models\Collection;

class CollectionAssetEquipmentController extends APIController
{
    /**
     * Load the Equipment
     */
    public function equipmentItems()
    {
        $assetId = request()->asset_id ?? '';

        request()->request->add(['sort_by' => 'name']);
        $results = Collection::where('slug', 'equipment')
            ->get();

        $results = $results->map([$this, 'mapFieldsToValues']);

        $assetIds = empty($assetId) ? [] : (new CollectionAssetController)->assetIdList($assetId);

        request()->query->remove('asset_id');

        if (!empty($assetIds)) {
            $results = $results->filter(function ($item) use ($assetIds) {
                return (isset($item->asset_id) && in_array($item->asset_id, $assetIds));
            });
        }
        return $this->showAll($results);
    }

    /**
     * Load the Equipment
     */
    public function hirerEquipment()
    {
        $results = Collection::where('slug', 'equipment')
        // ->with('collections')
        // ->where(function ($where) {
        //     $where->where('parent_id', '')->orWhere('parent_id', null);
        // })
            ->get();
        $results = $results->map([$this, 'mapFieldsToValues']);

        return $this->showAll($results);
    }

    public function formatResults(&$collection)
    {
        // $collection->each(function (&$item) {
        //     if (!empty($item->collections)) {
        //         $children = $this->formatResults($item->collections);
        //         if (!empty($children->count())) {
        //             $item->asset = $children;
        //         }
        //         unset($item->collections);
        //     }
        // });

        return $collection->map([$this, 'mapFieldsToValues']);
    }
}
