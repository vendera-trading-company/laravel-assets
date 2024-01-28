<?php

namespace VenderaTradingCompany\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'laravel_asset_pdfs';

    protected $casts = [
        'meta' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->header?->delete();
            $model->main?->delete();
            $model->footer?->delete();
        });
    }

    public function header()
    {
        return $this->belongsTo(Markdown::class, 'header_id');
    }

    public function main()
    {
        return $this->belongsTo(Markdown::class, 'main_id');
    }

    public function footer()
    {
        return $this->belongsTo(Markdown::class, 'footer_id');
    }
}
