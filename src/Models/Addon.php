<?php

namespace Obelaw\Twist\Models;


use Obelaw\Twist\Base\BaseModel;

class Addon extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'twist_addons';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'pointer',
        'panels',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'panels' => 'json',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
