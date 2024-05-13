<?php
namespace App\Repositories;

use App\Repositories\Repositories as Repositories;
use App\Model\Admin;

use Illuminate\Support\Facades\Redis;
use App\Repositories\RedisRepository;
use App\Model\Tag;
use DB;
use App\Services\ImageHelper as ImageService;
use Illuminate\Support\Carbon;

class AdminRepository extends Repositories{
	protected $redisRepository;
    function __construct(){
        parent::__construct();
        $this->redisRepository = new RedisRepository;
    }
    function create($input, $role_ids){
        try{
            $admin = Admin::create($input);
            $this->update_role($admin->id, [],$role_ids);
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }
    
    function update($admin_id, $input){
        try{
            Admin::withTrashed()->whereId($admin_id)->update($input);
            return true;
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }
    function update_role($admin_id, $detach, $attach){
        try{
            $admin = Admin::withTrashed()->find($admin_id);
            $admin->roles()->detach($detach);
            $admin->roles()->attach($attach);
            return true;
        }
        catch (Exception $e)
        {
            echo 'Caught exception: '.  $e->getMessage()."\n";
        }
        return false;
    }

    function destroy($admin_id, $option = 'soft', $old_roles=[]){
        try{
            $admin = Admin::withTrashed()->findOrFail($admin_id);
            $admin->status = config('constant.status.off');
            $admin->save();
            if($option == 'force'){
                if(count($old_roles) > 0) $this->update_role($admin_id, $old_roles,[]);

                //delete admin avatar
                if($admin->avatar != NULL )$this->imageService->removeOldImage(config('constant.image.admin').config('constant.image.avatar'), $admin->avatar);

                return $admin->forceDelete();
            }
            else return $admin->delete();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        return false;
    }
    function restore($admin_id){
        try{
            $admin = Admin::onlyTrashed()->whereId($admin_id);
            $admin->restore();

            $admin = Admin::findOrFail($admin_id);
            $admin->status = config('constant.status.on');
            return $admin->save();
            
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * getAllAdminWithRequest
     * @return true
     */
    public function getAllAdminWithRequestEloquent($request)
    {
        $query = Admin::withTrashed()->select('admins.*')->withTrashed();
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
        if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }
        if ($request->has('role_search') && $request->get('role_search') != '' && $request->get('role_search') != 0) {
            $query->leftJoin('role_admin','admins.id','=','role_admin.admin_id')->where('role_admin.role_id',"{$request->get('role_search')}");
        }
        if ($request->has('email') && $request->get('email') != '') {
            $query->where('email', 'like', "%{$request->get('email')}%");
        }
        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }
        return $query->get();
    }

	function getAllWithRequest($request){
		$query = DB::table('admins')->select(['id', 'name', 'status', 'updated_at']);
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
		if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }
        if ($request->has('email') && $request->get('email') != '') {
            $query->where('email', 'like', "%{$request->get('email')}%");
        }
        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }
        

		return $query;
	}
    function getAlActive(){
        return DB::table('admins')->whereStatus(config('constant.status.on'))->get()->keyBy('id')->toArray();
    }
    function getRootCategory(){
        return DB::table('admins')->where('parent_id',0)->get();
    }
    function getAdminActiveByRole($role='', $select = ['admins.id','admins.name','admins.phone'])
    {
        return DB::table('admins')->select($select)
                        ->leftJoin('role_admin','admins.id','=','role_admin.admin_id')
                        ->leftJoin('roles','roles.id','=','role_admin.role_id')
                        ->where('roles.slug',$role)
                        ->where('admins.status',config('constant.status.on'))
                        ->orderBy('admins.id', 'asc')
                        ->get();
        
    }
    
}