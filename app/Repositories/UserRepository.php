<?php
namespace App\Repositories;

use App\Repositories\Repositories as Repositories;
use App\Model\User;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Carbon;
use DB;
class UserRepository extends Repositories{


    function createUser($input){
        return User::create($input);
    }
    function updateUser($user_id, $input){
        return User::whereId($user_id)->update($input);
    }
    function destroy($user_id, $option){
        try{
            $user = User::withTrashed()->findOrFail($user_id);
            $user->status = config('constant.status.off');
            $user->save();
            //xoa cac thong tin lien quan den user roi moi xoa user

            if($option == 'force'){
                //delete user avatar
                if($user->avatar != NULL )$this->imageService->removeOldImage(config('constant.image.user'), $user->avatar);

                return $user->forceDelete();
            }
            else return $user->delete();
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    function restore($user_id){
        try{
            $admin = User::onlyTrashed()->whereId($user_id);
            $admin->restore();

            $admin = User::findOrFail($user_id);
            $admin->status = config('constant.status.on');
            return $admin->save();

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

	function getAllUserWithRequestEloquent($request){
		$query = User::withTrashed()->select(['id', 'name','slug', 'email','address', 'birthday', 'phone','status', 'updated_at','deleted_at']);
        $date_search = getDateTimeToSearch($request->get('from_datetime'), $request->get('to_datetime'));
        $start = Carbon::now()->startOfWeek()->day;
        $start_month = Carbon::now()->month;

        $end = Carbon::now()->endOfWeek()->day;
        $end_month = Carbon::now()->endOfWeek()->month;
		if ($request->has('name') && $request->get('name') != '') {
            $query->where('name', 'like', "%{$request->get('name')}%");
        }

        if ($request->has('email') && $request->get('email') != '') {
            $query->where('email', 'like', "%{$request->get('email')}%");
        }
        if ($request->has('phone') && $request->get('phone') != '') {
            $query->where('phone', 'like', "%{$request->get('phone')}%");
        }

        if ($request->has('from_datetime') && $request->get('from_datetime') != '') {
           $query->where('updated_at', '>=', $date_search['from']);
        }

        if ($request->has('to_datetime') && $request->get('to_datetime') != '') {
           $query->where('updated_at', '<=', $date_search['to']);
        }
        
        if($request->has('time_birthday_search') && $request->time_birthday_search == 0) //today
        {
            $query->whereMonth('birthday', $start_month)->whereDay('birthday', Carbon::now()->day);
        }

        if($request->has('time_birthday_search') && $request->time_birthday_search == 1) //this week
        {
            if($start_month != $end_month){
                $end = Carbon::now()->endOfMonth()->day;
            }
            if($start > 27) $start=01;
            $query->whereMonth('birthday', $start_month)->whereDay('birthday','>=', $start)->whereDay('birthday','<=', $end);

        }
        if($request->has('time_birthday_search') && $request->time_birthday_search == 2) //this month
        {
            $query->whereMonth('birthday', $start_month);
        }

		return $query->orderBy('created_at','DESC');
	}
    function getAllUserActive(){
    	// return User::active()->get(); //use scope
        return DB::table('users')->select('id','name', 'email','birthday','slug','address','phone')->where('status',1)->orderby('id','desc')->get();
    }
    function getUserbyID($user_id){
        return DB::table('users')->select('*')->whereId($user_id)->first();
    }
}
