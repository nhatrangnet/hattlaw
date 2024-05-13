<?php
namespace App\Repositories;

use App\Model\User;
use Illuminate\Support\Facades\Redis;
use App\Repositories\RedisRepository;
use App\Model\Role;
use DB;
class RoleRepository{
	protected $redisRepository;
    // DB::table('roles')->whereRaw("JSON_EXTRACT(`permissions`,'$.super_admin') = true")->get();
    // DB::update("UPDATE roles SET permissions = JSON_SET(permissions, '$.color2', 'cyan', '$.color3', 'black') WHERE id='4' ");
    // DB::table('roles')->where('id',4)->update(['permissions->super_admin' => false,
                                                    //     'permissions->color2' => 'cyan',
                                                    //     'permissions->color3' => 'black'
                                                    // ]);
    function __construct(){
        $this->redisRepository = new RedisRepository;
    }
    function create($input){
        if(Role::create($input)){
            return true;
        }
        return false;
    }
    function update($role_id, $input){
        $permissions=[];
        if(isset($input['permissions']) && count($input['permissions']) > 0){
            $permissions = array_combine(
                array_map(function($k){ return 'permissions->'.$k; }, array_keys($input['permissions'])),
                $input['permissions']
            );
        }
        unset($input['permissions']);
        
        try{
            Role::withTrashed()->whereId($role_id)->update($input);
            if(count($permissions) > 0){
                DB::insert("UPDATE roles SET permissions = '{}' WHERE id=:role_id ", ['role_id' => $role_id] );
                DB::table('roles')->where('id',$role_id)->update($permissions);
            }
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        }
    }
    function destroy($role_id, $option){

        try{

            $role = Role::withTrashed()->findOrFail($role_id);
            $role->status = config('constant.status.off');
            $role->save();

            if($option == 'force'){
                DB::table('role_admin')->where('role_id', $role_id)->delete();
                return $role->forceDelete();
            }
            else return $role->delete();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }
    function restore($role_id){
        try{
            $role = Role::onlyTrashed()->whereId($role_id);
            $role->restore();

            $role = Role::findOrFail($role_id);
            $role->status = config('constant.status.on');
            return $role->save();
            
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        
    }

	function getAllWithRequestEloquent($request){
		$query = Role::select(['id', 'name', 'status', 'updated_at','deleted_at'])->withTrashed();
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
		if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }
        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }
        

		return $query->get();
	}
    function getAlActive(){
        return DB::table('roles')->whereStatus(config('constant.status.on'))->get()->keyBy('id')->toArray();
    }
    function getbyID($id){
        return DB::table('roles')->whereId($id)->first();
    }
    function getbyAttribute($atr, $value){
        return DB::table('roles')->where($atr, $value);
    }

    function adminPerRole($role_id){
    	return DB::table('blog_news')->select('id','category_id','title','slug','summary','image','hit','updated_at')
    	    ->whereStatus(config('constant.status.on'))->whereRaw('FIND_IN_SET('.$role_id.',tags)')->get();
    }
    /**
     * getRolesbyAdmin
     * @return true
     */
    public function getRolesbyAdmin($admin_id)
    {
        $sql = "SELECT r.name, r.slug, r.id
        FROM roles r 
        left join role_admin ra ON ra.role_id = r.id
        left join admins a ON ra.admin_id = a.id
        WHERE r.status = 1 AND a.id = '{$admin_id}'";
        
        return DB::select($sql);
    }
}