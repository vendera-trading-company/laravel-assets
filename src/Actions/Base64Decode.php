<?php

namespace VenderaTradingCompany\LaravelAssets\Actions;

use Exception;
use VenderaTradingCompany\PHPActions\Action;

/**
 * @data mixed $data
 * @response mixed $data
 */
class Base64Decode extends Action
{
    protected $secure = [
        'data',
    ];

    public function handle()
    {
        $data = $this->getData('data');

        if (empty($data)) {
            return null;
        }

        try {
            $data = preg_replace('/^.+\w+;base64,/', '', $data);
            $data = str_replace(' ', '+', $data);

            $data = base64_decode($data);

            return [
                'data' => $data
            ];
        } catch (Exception $e) {
        }

        return null;
    }
}
