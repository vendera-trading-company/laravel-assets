<?php

namespace VenderaTradingCompany\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

abstract class Asset extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'laravel_asset_assets';

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if (empty($model->relative_path)) {
                return null;
            }

            if (!empty($model->relative_path)) {
                return Storage::disk($model->disk)->delete($model->relative_path);
            }

            return Storage::delete($model->relative_path);
        });
    }

    public function url(): string | null
    {
        if (empty($this->relative_path)) {
            return null;
        }

        if (!empty($this->relative_path)) {
            return Storage::disk($this->disk)->url($this->relative_path);
        }

        return Storage::url($this->relative_path);
    }
}