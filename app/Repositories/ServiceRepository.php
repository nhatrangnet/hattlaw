<?php
namespace App\Repositories;

use App\Repositories\Repositories as Repositories;
use App\Model\Service;
use Illuminate\Support\Facades\Redis;
use Yajra\Datatables\Datatables;
use DB;
class ServiceRepository extends Repositories{


    function createService($input){
        return Service::create($input);
    }
    function updateService($service_id, $input){
        return Service::whereId($service_id)->update($input);
    }
    function destroy($service_id, $option){
        try{
            $service = service::withTrashed()->findOrFail($service_id);
            $service->status = config('constant.status.off');
            $service->save();
            //xoa cac thong tin lien quan den service roi moi xoa service

            if($option == 'force'){
                //delete service avatar
                if($service->avatar != NULL )$this->imageService->removeOldImage(config('constant.image.service'), $service->avatar);

                return $service->forceDelete();
            }
            else return $service->delete();
        }
        catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    function restore($service_id){
        try{
            $admin = service::onlyTrashed()->whereId($service_id);
            $admin->restore();

            $admin = service::findOrFail($service_id);
            $admin->status = config('constant.status.on');
            return $admin->save();

        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

	function getAllServiceWithRequestEloquent($request){
		$query = Service::withTrashed()->select(['id', 'name','slug','status', 'updated_at','deleted_at']);
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

		return $query;
	}
    function getParentService(){
        return DB::table(config('constant.database.service'))->where('parent_id',0)->get();
    }
}
