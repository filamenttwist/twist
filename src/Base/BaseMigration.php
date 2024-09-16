<?php

namespace Obelaw\Twist\Base;

use Illuminate\Database\Migrations\Migration;
use Obelaw\Twist\Facades\Twist;

abstract class BaseMigration extends Migration
{
    /**
     * Table prefix.
     *
     * @var string $prefix
     */
    protected string $prefix = '';

    /**
     * Create a new instance of the migration.
     */
    public function __construct()
    {
        $this->prefix = config('obelaw.database.table_prefix', Twist::getPrefixTable());
    }
}
