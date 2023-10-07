<?php

namespace PersistentRequest\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $serialize_data
 * @property int $count_request
 * @property string $created_at
 * @property string $updated_at
 */
class RequstModel extends Model
{
    protected $table = 'persistent_request';
}