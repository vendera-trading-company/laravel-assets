<?php

namespace VenderaTradingCompany\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Model;

class Markdown extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'markdowns';

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->raw?->delete();
            $model->formatted?->delete();
        });
    }

    public function raw()
    {
        return $this->belongsTo(File::class, 'raw_id');
    }

    public function formatted()
    {
        return $this->belongsTo(File::class, 'formatted_id');
    }
}
