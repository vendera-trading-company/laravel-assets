<?php

namespace VenderaTradingCompany\LaravelAssets\Models;

use Illuminate\Database\Eloquent\Model;
use VenderaTradingCompany\LaravelAssets\Actions\File\FileDestroy;
use VenderaTradingCompany\PHPActions\Action;

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
            Action::build(FileDestroy::class)->data([
                'id' => $model->raw_id,
            ])->run();
            Action::build(FileDestroy::class)->data([
                'id' => $model->formatted_id,
            ])->run();
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
