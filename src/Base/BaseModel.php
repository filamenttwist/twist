<?php

namespace Obelaw\Twist\Base;

use Illuminate\Database\Eloquent\Model;
use Obelaw\Twist\Facades\Twist;

abstract class BaseModel extends Model
{
    /**
     * Create a new instance of the Model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($connection = Twist::getConnection()) {
            $this->setConnection($connection);
        }

        $this->setTable(config('obelaw.database.table_prefix', Twist::getPrefixTable()) . $this->getTable());
    }
}
