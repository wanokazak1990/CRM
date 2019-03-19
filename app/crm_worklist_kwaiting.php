<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_kwaiting extends Model
{
    //
    protected $fillable = ['kredit_id', 'author_id', 'kreditor_id', 'payment', 'sum', 'date_in', 'status_date', 'status_id', 'day_count', 'date_action', 'date_offer', 'product'];
}
