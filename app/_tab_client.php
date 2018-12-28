<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _tab_client extends Model
{
    //
    static public $tab_index = 2;

    protected $fillable = array("*");

    public function model()
    {
    	return $this->hasOne('App\oa_model','id','desired_model');
    }

    public function manager()
    {
    	return $this->hasOne('App\User','id','manager_id');
    }

    public function action()
    {
    	return $this->hasOne('App\crm_assigned_action','id','assigned_action_id');
    }

    public function date()
    {
    	return date('d.m.Y',$this->action_date);
    }

    public static function getTitle()
    {
    	$res = crm_all_field::where('type_id',self::$tab_index)->get();
    	return $res;
    }
}
