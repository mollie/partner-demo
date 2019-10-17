<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int user_id
 * @property string access_token
 * @property string refresh_token
 * @property DateTime expires_at
 * @property DateTime created_at
 * @property DateTime updated_at
 */
class MollieAccessToken extends Model
{
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected function setExpiresAtAttribute(DateTime $value)
    {
        $this->attributes['expires_at'] = $value;
    }
}
