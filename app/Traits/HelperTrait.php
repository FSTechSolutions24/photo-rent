<?php

namespace App\Traits;

use Carbon\Carbon;

trait HelperTrait
{
    public $foreign_key = 0;

    /**
     * Store dynamic table records
     */
    public function storeDynamicTableRecords($modelClass, $foreignKey, $foreignId, $items)
    {
        // Reset existing rows for this parent
        $modelClass::where($foreignKey, $foreignId)->delete();
        // dd($items);

        // Reinsert dynamically
        foreach ($items['rows'] as $item) {

            // Ensure foreign key is included
            $item[$foreignKey] = $foreignId;

            // Create new record (dynamic columns)
            $modelClass::create($item);
        }
    }
}
