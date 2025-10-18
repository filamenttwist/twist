<?php

namespace Twist\Base;

use Illuminate\Database\Eloquent\Model;
use Twist\Facades\Twist;

abstract class BaseModel extends Model
{
    /**
     * Table postfix.
     *
     * @var string $postfix
     */
    protected string $postfix = '';

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

        $this->setTable(config('obelaw.database.table_prefix', Twist::getPrefixTable()) . $this->postfix . $this->getTable());
    }
}
