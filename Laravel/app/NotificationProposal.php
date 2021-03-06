<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationProposal extends Model
{

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notification_proposal';

    /**
     * The primary key associated with the model.
     *
     * @var string
     */
    protected $primaryKey = ['idproposal', 'idnotification'];

    /**
     * Indicates wheter the primary key should
     * be an incrementing integer value
     *
     * @var string
     */
    public $incrementing = false;
}
