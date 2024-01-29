<?php

namespace VenderaTradingCompany\LaravelAssets\Models;

class File extends Asset
{
    protected $table = 'files';

    public function toArray()
    {
        $array = parent::toArray();

        $array['content'] = $this->content();

        return $array;
    }
}
