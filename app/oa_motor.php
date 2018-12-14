<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class oa_motor extends Model
{
    //
    protected $fillable = ['type_id','size','valve','power','transmission_id','wheel_id','brand_id'];
    public $timestamps = false;

    public function brand()
    {
    	return $this->belongsTo('App\oa_brand');
    }

    public function transmission()
    {
    	return $this->belongsTo('App\type_transmission');
    }

    public function wheel()
    {
    	return $this->belongsTo('App\type_wheel');
    }

    public function type()
    {
    	return $this->belongsTo('App\type_motor');
    }

    public function name()
    {
        if(isset($this->type) && isset($this->transmission) && isset($this->wheel)) :
        	$mas[] = $this->brand->name;
        	$mas[] = $this->type->name;
        	$mas[] = $this->size.' л.';
        	$mas[] = '('.$this->power.' л.с.)';
        	$mas[] = $this->valve.' кл.';
        	$mas[] = $this->transmission->name;
        	$mas[] = $this->wheel->name;
        	return implode(' ',$mas);
        endif;
        return 'Не полный мотор';
    }

    public function nameForSelect()
    {
        if(isset($this->type) && isset($this->transmission) && isset($this->wheel)) :
            $mas[] = $this->type->name;
            $mas[] = $this->size.' л.';
            $mas[] = '('.$this->power.' л.с.)';
            $mas[] = $this->valve.' кл.';
            $mas[] = $this->transmission->name;
            $mas[] = $this->wheel->name;
            $name = implode(' ', $mas);
            return array('id'=>$this->id,'name'=>$name);
        endif;
    }

    public function forAdmin()
    {   
        if(isset($this->type) && isset($this->transmission) && isset($this->wheel)) :
            $mas[] = $this->type->name;
            $mas[] = $this->size.' л.';
            $mas[] = '('.$this->power.' л.с.)';
            $mas[] = $this->valve.' кл.';
            $mas[] = $this->transmission->name;
            $mas[] = $this->wheel->name;
            return implode(' ',$mas);
        endif;
        return 'Не полный мотор';
    }

    public function getEasyName()
    {
        if(isset($this->type) && isset($this->transmission) && isset($this->wheel)) :
            $mas[] = $this->size;
            $mas[] = '('.$this->power.' л.с.)';
            $mas[] = $this->transmission->name;
            $mas[] = $this->wheel->name;
            return implode(' ',$mas);
        endif;
        return 'Не полный мотор';
    }
}
