<?php
namespace App\Repositories;

use DB;
use App\Services\ImageHelper as ImageService;
use Illuminate\Support\Carbon;
use App\Model\Contact;

class Repositories{
    protected $imageService;
    function __construct(){
        $this->imageService = new ImageService;
    }

    function insertTableValue($table, $value=array()){
        if(!isset($value['created_at'])) $value['created_at'] = Carbon::now();
        if(!isset($value['updated_at'])) $value['updated_at'] = Carbon::now();
        echo '<pre>';print_r($value);die;
        return DB::table($table)->insert($value);
    }

    function insertTableValueGetid($table, $value=array()){
        if(!isset($value['created_at'])) $value['created_at'] = Carbon::now();
        if(!isset($value['updated_at'])) $value['updated_at'] = Carbon::now();
        return DB::table($table)->insertGetId($value);
    }

    function updateTableValue($table, $field, $value, $update=array()){
        if(!isset($update['updated_at'])) $update['updated_at'] = Carbon::now();
        return DB::table($table)->where($field, $value)->update($update);
    }
    function getAllTable($table, $select = ['*']){
        return DB::table($table)->select($select)->orderBy('id','asc')->get();
    }
    function getAllTableChunk($table, $select = ['*']){
        return DB::table($table)->select($select)->orderBy('id','asc');
    }
    function getAllTableActive($table, $select = ['*']){
        return DB::table($table)->select($select)->where('status', config('constant.status.on'))->orderBy('id','asc')->get();
    }
    function getbyID($table, $id, $select = ['*']){
        return DB::table($table)->select($select)->whereId($id)->first();
    }
    function getbyAttribute($table,$atr, $value, $select = ['*']){
        return DB::table($table)->select($select)->where($atr, $value);
    }

    function getbyAttributeActive($table,$atr, $value){
        return DB::table($table)->where($atr, $value)->where('status', config('constant.status.on'))->orderBy('id','asc')->get();
    }

    function getbyTableField($table, $condition= array(), $select = ['*']){
        if($table == null) return false;
        $query =  DB::table($table)->select($select);
        $limit = false;
        $orderby=false;

        if(!empty($condition)){
            foreach($condition as $field => $value){
                if($field == 'whereNotNull') $query->whereNotNull($value);
                elseif($field == 'limit') {
                    $limit=true;
                    continue;
                }
                elseif($field == 'orderby') {

                    $orderby=true;
                    continue;
                }
                elseif(is_array($value)) $query->whereIn($field, $value);
                else $query->where($field, $value);
            }
        }
        if($limit) return $query->limit($condition['limit'])->get();
        if($orderby){
            $valueOrder = explode('_',$condition['orderby']);
            return $query->orderBy($valueOrder[0],$valueOrder[1])->get();
        }
        else return $query->get();
    }
    function getonlyTableField($table, $condition= array(), $select = ['*']){
        if($table == null) return false;
        $query =  DB::table($table)->select($select);

        if(!empty($condition)){
            foreach($condition as $field => $value){
                if($field == 'whereNotNull') $query->whereNotNull($value);
                elseif(is_array($value)) $query->whereIn($field, $value);
                else $query->where($field, $value);
            }
        }
        return $query->first();
    }

    function get_data_paginate($table = 'blog_news', $condition=array(), $limit=0, $select='*', $orderby='created_at')
    {
        if($limit == 0) $limit = config('constant.item_per_page');

        $query = DB::table($table)->select($select);
        if(!empty($condition)){
            foreach($condition as $field => $value){
                if(is_array($value)) $query->whereIn($field, $value);
                else $query->where($field, $value);
            }
        }
        $query->orderby($orderby, 'desc');
        return $query->paginate($limit);
    }

    function deleteTableWhereIn($table, $field, $value=array() ){
        if($table == null) return false;
        return DB::table($table)->whereIn($field, $value)->delete();
    }


    public function searchStatisticUser($request)
    {
        $start = Carbon::now()->startOfWeek()->day;
        $start_month = Carbon::now()->month;

        $end = Carbon::now()->endOfWeek()->day;
        $end_month = Carbon::now()->endOfWeek()->month;

        if($request->has('time_birthday_search') && $request->time_birthday_search == 0) //this week
        {
            if($start_month != $end_month){
                $end = Carbon::now()->endOfMonth()->day;
            }

            $result = DB::table('users')->where('status', config('constant.status.on'))->whereMonth('birthday', $start_month)->whereDay('birthday','>=', $start)->whereDay('birthday','<=', $end)->get();
            if(count($result) == 0) $result = DB::table('users')->where('status', config('constant.status.on'))->whereMonth('birthday', $start_month)->whereDay('birthday','>=', $start)->whereDay('birthday','<=', Carbon::now()->add('7 days')->day)->get();

            return $result;
        }
        if($request->has('time_birthday_search') && $request->time_birthday_search == 1) //this month
        {
            return DB::table('users')->where('status', config('constant.status.on'))->whereMonth('birthday', $start_month)->get();
        }

    }

    public function incrementData($table, $condition=array(), $field, $value){
        return DB::table($table)->where($condition)->increment($field, $value);
    }
    function createContact($input){
        try{
            Contact::create($input);
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }

}
