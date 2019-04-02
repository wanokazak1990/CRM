<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class crm_worklist_kwaiting extends Model
{
    //
    protected $fillable = ['kredit_id', 'author_id', 'kreditor_id', 'payment', 'sum', 'date_in', 'status_date', 'status_id', 'day_count', 'date_action', 'date_offer', 'product'];

    public function arrayKreditResult()
    {
    	return [1=>'Отклонён',2=>'Одобрен'];
    }

    public function status()
    {
    	switch ($this->status_id) {
    		case '1':
    			return 'Отклонён';
    			break;
    		case '2':
    			return 'Одобрен';
    			break;
    		
    		default:
    			return '';
    			break;
    	}
    }

    
    public function author()
    {
    	return $this->hasOne('App\user','id','author_id');
    }

    public function kreditor()
    {
    	return $this->hasOne('App\crm_partner','id','kreditor_id');
    }
    
}
