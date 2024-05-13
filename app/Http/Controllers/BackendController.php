<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Theme, DB, Crypt;


class BackendController extends Controller
{
    /**
     * Theme instance.
     *
     * @var \Teepluss\Theme\Theme
     */
    protected $theme;

    public function __construct()
    {
      parent::__construct();
    	// $this->middleware('admin.checklogin:admin,dashboard/login')->except('getRegister','postRegister');// middleware:guards, redirect link if failed
    	try {
            $this->theme = Theme::uses(config('theme.themeAdmin'))->layout('layout');
        }
        catch (\Exception $e){
            throw new TemplateNotFound;
        }

        $this->theme->asset()->add('default-style', 'css/app.css');
        $this->theme->asset()->add('backend-style', 'css/backend.css');

        $this->theme->asset()->add('default-script', 'js/app.js');
        $this->theme->asset()->add('datatable-script', 'js/datatable.js');
        $this->theme->asset()->container('footer')->add('backend-script', 'js/backend.js');

        $this->theme->asset()->cook('lightbox',function($asset){
            $asset->add('lightbox-style', 'css/lightbox.css');
            $asset->add('lightbox-script', 'js/lc_lightbox.lite.min.js');
        });
        
        $this->theme->asset()->cook('datatable',function($asset){
            $asset->add('datatable-style', '//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
            $asset->add('datatable-script', '//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js');
        });

        $config = json_decode($this->redisRepository->getConfig('config'),true);
        $company_name = !empty($config['company_name'])?$config['company_name']:config('constant.main.app_name');
        $this->theme->set('company_name',$company_name);
        $this->theme->set('keywords',$config['metakey']??config('constant.meta.default.keywords'));
        $this->theme->set('description',$config['metades']??config('constant.meta.default.description'));
        $this->theme->set('robot',$config['metarobot']??config('constant.meta.default.metarobot'));
        $this->theme->set('author',config('constant.meta.default.author'));

        $this->theme->breadcrumb()->add($company_name, \URL::to('/'));
        $this->theme->breadcrumb()->setTemplate('
            <ul class="breadcrumb breadcrumb_top">
            <?php foreach ($crumbs as $i => $crumb) { ?>
            @if ($i != (count($crumbs) - 1))
            <li><a href="{{ $crumb["url"] }}">{{ $crumb["label"] }}</a><span class="divider">/</span></li>
            @else
            <li class="active">{{ $crumb["label"] }}</li>
            @endif
            <?php } ?>
            </ul>
            ');

    }
    /**
     * Function description
     * @return true
     */
    public function check_auth_permis($permiss=[])
    {
        $auth = Auth::guard('admin')->user();
        $continue = false;
        if($auth->isSuperAdmin()) $continue = true;//app/Model/Admin.php
        elseif($auth->hasAccess($permiss)) $continue = true;
        abort_if(!$continue, 403, 'Unauthorized.');
        return $continue;
    }
}
