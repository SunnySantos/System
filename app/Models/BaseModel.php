<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use Auditable;

    /**
     * Delete multiple models safely (with events).
     *
     * @param  array|Illuminate\Support\Collection  $ids
     * @param  int  $chunkSize
     * @return void
     */
    public static function deleteMany(array $ids, int $chunkSize = 100): void
    {
        static::whereIn('id', $ids)
            ->chunkById($chunkSize, function ($models) {
                foreach ($models as $model) {
                    $model->delete();
                }
            });
    }
}
