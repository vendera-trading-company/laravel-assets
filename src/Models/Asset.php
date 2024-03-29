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

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if (empty($model->relative_path)) {
                return;
            }

            if (!empty($model->disk)) {
                Storage::disk($model->disk)->delete($model->relative_path);
                return;
            }

            Storage::delete($model->relative_path);
            return;
        });
    }

    public function url(): string | null
    {
        if (empty($this->relative_path)) {
            return null;
        }

        if (!empty($this->disk)) {
            return Storage::disk($this->disk)->url($this->relative_path);
        }

        return Storage::url($this->relative_path);
    }

    public function path(): string | null
    {
        if (empty($this->relative_path)) {
            return null;
        }

        if (!empty($this->disk)) {
            return Storage::disk($this->disk)->path($this->relative_path);
        }

        return Storage::path($this->relative_path);
    }

    public function content(): string | null
    {
        if (!empty($this->data)) {
            return $this->data;
        }

        if (empty($this->relative_path)) {
            return null;
        }

        if (!empty($this->disk)) {
            return Storage::disk($this->disk)->get($this->relative_path);
        }

        return Storage::get($this->relative_path);
    }

    public function download(string | null $name = null, array $headers = []): mixed
    {
        if (!empty($this->data)) {
            $data = $this->data;

            return response()->stream(function () use ($data) {
                fpassthru($data);
            }, 200, $headers);
        }

        if (empty($this->relative_path)) {
            return null;
        }

        if (!empty($this->disk)) {
            return Storage::disk($this->disk)->download($this->relative_path, $name, $headers);
        }

        return Storage::download($this->relative_path, $name, $headers);
    }
}
