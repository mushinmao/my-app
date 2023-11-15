<?php

namespace App\API\EloquentAPI;

use App\AppUrlShortener\Interface\ProviderInterface;
use App\DB\Eloquent\ShortLinks;

class ShortenerBridge implements ProviderInterface
{
    public function save(array $data):void
    {
        $model = new ShortLinks();
        foreach ($data as $key => $value) {
            $model->{$key} = $value;
        }

        $model->save();
    }
}