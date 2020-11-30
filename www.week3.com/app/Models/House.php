<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use SoftDeletes;

    public $table='houses';

    protected $guarded=[];

    protected $dates=['deleted_at'];

    public static function dest(){
        return self::get()->toArray();
    }

    public function fordest(array $data,int $pid=0,int $level = 1){
        static $newdata=[];
        foreach ($data as $k=>$v){
            if($v['pid'] == $pid){
                $v['level']=$level;
                $newdata[]=$v;
                unset($data[$k]);
                $this->fordest($data,$v['id'],$level+1);
            }
        }
        return $newdata;
    }
}
