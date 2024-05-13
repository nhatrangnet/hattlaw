<?php
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

function getDateTimeToSearch($from_datetime, $to_datetime){
    $return = [];
    if(isset($from_datetime)) $return['from'] = Carbon::parse($from_datetime)->format(config('constant.ymd'));
    if(isset($to_datetime)) $return['to'] = Carbon::createFromFormat('Y-m-d', $to_datetime)->toDateTimeString();

    return $return;
}
function timeStatisticSearch()
{
    return [
        999 => trans('form.all_time'),
        0 => trans('form.this_day'),
        1 => trans('form.this_week'),
        2 => trans('form.this_month'),
        // 3 => trans('form.last_month'),
    ];
}

function phone_format($number){
  if($number <> 000 && strlen((string)$number) > 7) return substr($number, -10, -7) . "-" . substr($number, -7, -4) . "-" . substr($number, -4);
  else return $number;
}
function getFirstandLastDate($year, $month, $week) {

    $thisWeek = 1;

    for($i = 1; $i < $week; $i++) {
        $thisWeek = $thisWeek + 7;
    }

    $currentDay = date('Y-m-d',mktime(0,0,0,$month,$thisWeek,$year));

    $monday = strtotime('monday this week', strtotime($currentDay));
    $sunday = strtotime('sunday this week', strtotime($currentDay));

    $weekStart = date('d M y', $monday);
    $weekEnd = date('d M y', $sunday);

    return $weekStart . ' - ' . $weekEnd;
}
function getImageLink($category, $image_name){
    // if(Storage::exists($category.'/'.$image_name)){
        return Storage::url($category.'/'.$image_name);
    // }
    // return Storage::url(config('constant.no-image'));
}
function order_type_list($id = null)
{
    $return = [
        1 => trans('form.order_le'),
        2 => trans('form.order_le_overtime'),
        3 => trans('form.order_si'),
        4 => trans('form.order_si_overtime'),
        5 => trans('form.order_store'),
    ];
    if(!empty($id)) return $return[$id];
    else return $return;
}
function order_status_list($id = null)
{
    $return = [
        999 => trans('form.all_time'),
        1 => trans('form.order_proccess'),
        2 => trans('form.order_success'),
        3 => trans('form.order_fail'),
        4 => trans('form.order_owe'),
    ];
    if(!empty($id)) return $return[$id];
    else return $return;
}

/**
 * Function description
 * @return true
 */
function check_admin_logged_with_permiss($admin_logged_session, $permiss=[])
{
    if(!isset($admin_logged_session['permiss'])) return false;
    if( array_key_exists(config('constant.permissions.role.super_admin'), $admin_logged_session['permiss']) !== false) return true;

    if(count($permiss) >0){
        foreach($permiss as $per){
            // if( array_key_exists($per, $admin_logged_session['permiss']) !== false ) return true;
            return $admin_logged_session['permiss'][$per] ?? false;
        }
    }

    return false;
}
/**
 * Function description
 * @return true
 */
function list_permissions()
{
    $return =['super_admin' => 'super_admin'];
    $permissions = config('constant.permissions.content');
    $action = ['_view','_create','_update','_soft_delete','_force_delete'];
    foreach($permissions as $permiss){
        foreach($action as $val){
            $return[$permiss.$val] = $permiss.$val;
        }
    }

    return $return;
}
/**
 * metarobots
 * @return true
 */
function metarobot()
{
    return [
        'index, follow' => 'index, follow',
        'noindex, follow' => 'noindex, follow',
        'index, nofollow' => 'index, nofollow',
        'noindex, nofollow, archive' => 'noindex, nofollow, archive',
        'noindex, nofollow, noarchive' => 'noindex, nofollow, noarchive',
    ];
}

function getMenuActive($patterns, $activeClass = "active", $except = '')
{
    $currentRequest = Request::url();
    if (!$currentRequest) {
        return false;
    }
    if (!is_array($patterns)) {
        $patterns = array($patterns);
    }
    foreach ($patterns as $p) {
        if (Str::is($p, $currentRequest) && (empty($except) || !Str::is($except, $currentRequest))) {
            return $activeClass;
        }
    }
    return false;
}
function mahoa_giaima($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'asbjc#%*!@!)_$%asf12&SDK';
    $secret_iv = 'indsf345@#$$sdf!(&';

    //hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ($action == 'mahoa') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'giaima') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function mahoa($a) {
    return $this->mahoa_giaima('mahoa', $a);
}
function giaima($b) {
    return $this->mahoa_giaima('giaima', $b);
}

function countTotal($subtotal=null, $shipping_charge=null, $tax=null, $discount_percent = null, $discount = null)
{
    $tax_val=0;
    if($tax>0){
        $tax_val = ($subtotal*$tax)/100;
    }
    
    if($discount > 0){
        $subtotal -= $discount;
    }
    if($discount_percent > 0){
        $subtotal = $subtotal - ($subtotal*$discount_percent)/100;
    }
    return $subtotal+$shipping_charge+$tax_val;

}
/**
 * ham bo dau tieng viet
 * @param str $str vd vi?t nam
 * @return str thành viet-nam
 */
function bodau($str) {
    $chars = array(
        'a' => array('?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'á', 'à', '?', 'ã', '?', 'â', 'a', 'Á', 'À', '?', 'Ã', '?', 'Â', 'A'),
        'e' => array('?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'é', 'è', '?', '?', '?', 'ê', 'É', 'È', '?', '?', '?', 'Ê'),
        'i' => array('í', 'ì', '?', 'i', '?', 'Í', 'Ì', '?', 'I', '?'),
        'o' => array('?', '?', '?', '?', '?', '?', '?', '?', 'Ô', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'ó', 'ò', '?', 'õ', '?', 'ô', 'o', 'Ó', 'Ò', '?', 'Õ', '?', 'Ô', 'O'),
        'u' => array('?', '?', '?', '?', '?', '?', '?', '?', '?', '?', 'ú', 'ù', '?', 'u', '?', 'u', 'Ú', 'Ù', '?', 'U', '?', 'U'),
        'y' => array('ý', '?', '?', '?', '?', 'Ý', '?', '?', '?', '?'),
        'd' => array('d', 'Ð')
    );
    foreach ($chars as $key => $arr)
        foreach ($arr as $val)
            $str = str_replace($val, $key, strtolower($str));
    $str = preg_replace("/[^a-z0-9]/", '-', $str);
    return $str;
}
/**
 * cat chuoi qua dai
 * @param str $str
 * @param int $len
 * @return str
 */

function subString($text, $len)
{
  mb_internal_encoding('UTF-8'); 
  if( (mb_strlen($text, 'UTF-8') > $len) ) {  

    $text = mb_substr($text, 0, $len, 'UTF-8').'..';
    //$text = mb_substr($text, 0, mb_strrpos($text,0, " ", 'UTF-8'), 'UTF-8').'..';
  } 
  return $text;
}
/**
 * hight light search
 * @param str $text
 * @param array $words
 * @return type
 */
function highlightWords($text, $words)
{
    /*** loop of the array of words ***/
    foreach ($words as $word)
    {
        /*** quote the text for regex ***/
        $word = preg_quote($word);
        /*** highlight the words ***/
        $text = preg_replace("/\b($word)\b/i", '<span style="background:#ffcc33">\1</span>', $text);
    }
    /*** return the text ***/
    return $text;
}
function check_valid_url($url = false){
    if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
        return false;
    }
    return true;
}

function view_log($log) {
    file_put_contents(__DIR__.'/storage/logs/log.log', date("r").":\n".$log."\n---\n", FILE_APPEND);
}

function set_key($key, $data, $expires = null) {
    Redis::del($key);
    Redis::set($key, $data);
    if($expires != null) {
        Redis::expire($key, $expires);
    }
}

function get_key($key) {
    return Redis::get($key);
}
function del($key)
{
    Redis::del($key);
}
function hdel($key, $field)
{
    Redis::hdel($key,$field);
}
function hmset($key, $data)
{
    return Redis::hMSet($key,$data);
}
function hGetAll($k) {
    return Redis::hGetAll($k);
}
function rpush($k,$data) {
    return Redis::rpush($k,$data);
}
function hGet($k,$field) {
    return Redis::hGet($k,$field);
}
function hSet($k,$field,$value) {
    return Redis::hSet($k,$field,$value);
}
function lRange($key,$off,$limit) {
    return Redis::lrange($key,$off,$limit);
}
function zRange($key,$off,$limit) {
    return Redis::zRange($key,$off,$limit);
}
function sadd($key,$v) {
    return Redis::sadd($key, $v);
}
function lPush($key,$value) {
    return Redis::lpush($key, $value);
}
function lRem($key,$count = 0,$value) {
    return Redis::lrem($key,$count,$value);
}
function smembers($key){
    return Redis::smembers($key);
}
function expire($key, $value){
    Redis::expire($key, $value);
}
function exists($key)
{
    return Redis::exists($key);
}
function hExists($key, $field)
{
    return Redis::hExists($key, $field);
}

function redis_key($name, $user_id = null){
    switch ($name) {
        case 'config':return 'config';break;
        case 'blog_categories':return 'blog_categories';break;
        case 'blog_cat_news':return 'blog_cat_news';break;
        case 'list_role': return 'list_role';break;
        case 'list_tag':return 'list_tag';break;
    }
}
