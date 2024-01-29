<?php

namespace VenderaTradingCompany\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'pdfs';

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

    public function raw()
    {
        $header = $this->header?->raw?->content();
        $main = $this->main?->raw?->content();
        $footer = $this->footer?->raw?->content();

        $text = '';

        if (!empty($header)) {
            $text .= '<header>' . $header . '</header>' . PHP_EOL;
        }

        if (!empty($main)) {
            $text .= '<main>' . $main . '</main>' . PHP_EOL;
        }

        if (!empty($footer)) {
            $text .= '<footer>' . $footer . '</footer>';
        }

        return $text;
    }

    public function formatted()
    {
        $header = $this->header?->formatted?->content();
        $main = $this->main?->formatted?->content();
        $footer = $this->footer?->formatted?->content();

        $text = '';

        if (!empty($header)) {
            $text .= '<header>' . $header . '</header>' . PHP_EOL;
        }

        if (!empty($main)) {
            $text .= '<main>' . $main . '</main>' . PHP_EOL;
        }

        if (!empty($footer)) {
            $text .= '<footer>' . $footer . '</footer>';
        }

        return $text;
    }
}
