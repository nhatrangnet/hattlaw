<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Repositories\Blog\CategoryRepository as BlogCategoryRepository;
use Theme, Auth, Cache;

class FrontendController extends Controller
{
    protected $theme;
    protected $user_logged;
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->only('userboard');
        $this->middleware(function ($request, $next) {
            $this->user_logged = Auth::user();
            return $next($request);
        });

        // try {
            $this->theme = Theme::uses(env('APP_THEME', 'default'))->layout('layout');
        // }
        // catch (\Exception $e){
        //     throw new TemplateNotFound;
        // }

        $this->theme->asset()->add('default-style', 'css/app.css');
        $this->theme->asset()->add('font-style', '//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
        $this->theme->asset()->add('effect-style', 'css/effect.css');
        $this->theme->asset()->container('bottom-header')->add('frontend-style', 'css/frontend.css');

        // $this->theme->asset()->add('default-script', 'js/app.js');
        $this->theme->asset()->add('datatable-script', 'js/datatable.js');
        $this->theme->asset()->add('fontawesome-script', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js');
        $this->theme->asset()->container('footer')->add('frontend-script', 'js/frontend.js');

        //animate on scroll
        $this->theme->asset()->cook('aos', function($asset){
            $asset->add('aos-style','https://unpkg.com/aos@2.3.1/dist/aos.css');
            $asset->add('aos-script' ,'https://unpkg.com/aos@2.3.1/dist/aos.js');
        });

        $this->theme->asset()->cook('swiper',function($asset){
            $asset->add('swiper-style','https://unpkg.com/swiper/swiper-bundle.min.css');
            $asset->add('bswiper-script' ,'https://unpkg.com/swiper/swiper-bundle.min.js');
        });
        $this->theme->asset()->cook('lightbox',function($asset){
            $asset->add('lightbox-style', 'css/lightbox.css');
            $asset->add('lightbox-script', 'js/lc_lightbox.lite.min.js');
        });
        $this->theme->asset()->cook('datatable',function($asset){
            $asset->add('datatable-style', '//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
            $asset->add('datatable-script', '//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js');
        });
        

        $this->theme->asset()->cook('jgallery', function($asset){
            $asset->add('jgallery-script', 'js/jgallery.min.js');
        });

        
        $config = json_decode($this->redisRepository->getConfig('config'), true);
        $company_name = !empty($config['company_name'])?$config['company_name']:config('constant.main.app_name');
        $this->theme->set('company_name', $company_name);
        $this->theme->set('keywords',!empty($config['metakey'])?$config['metakey']:config('constant.meta.default.keywords'));

        $this->theme->set('description',!empty($config['metades'])?$config['metades']:config('constant.meta.default.description'));
        
        $this->theme->set('robot',!empty($config['metarobot'])?$config['metarobot']:config('constant.meta.default.metarobot'));
        $this->theme->set('author',config('constant.meta.default.author'));
        $this->theme->bind('config', function() use ($config){
            return $config;
        });

        
        $blog_categories = $this->repository->getAllTableActive(config('constant.database.blog_categories'));
        $this->theme->bind('blog_categories', function() use ($blog_categories){
            return $blog_categories;
        });

        $services_list = $this->repository->getAllTableActive(config('constant.database.service'))->toArray();
        $services=[];
        foreach($services_list as $service){
            if($service->parent_id == 0){
                $services[$service->id]['slug'] = $service->slug;
                $services[$service->id]['image'] = $service->image;
                $services[$service->id]['cover'] = $service->cover;
                $services[$service->id]['vi']['name'] = $service->name;
                $services[$service->id]['vi']['metakey'] = $service->metakey;
                $services[$service->id]['vi']['metades'] = $service->metades;
                $services[$service->id]['vi']['metarobot'] = $service->metarobot;

                $services[$service->id]['en']['name'] = $service->en_name;
                $services[$service->id]['en']['metakey'] = $service->en_metakey;
                $services[$service->id]['en']['metades'] = $service->en_metades;
                $services[$service->id]['en']['metarobot'] = $service->en_metarobot;

            }
            else{
                $services[$service->parent_id]['sub'][$service->id]['slug'] = $service->slug;
                $services[$service->parent_id]['sub'][$service->id]['image'] = $service->image;
                $services[$service->parent_id]['sub'][$service->id]['cover'] = $service->cover;
                $services[$service->parent_id]['sub'][$service->id]['vi']['name'] = $service->name;
                $services[$service->parent_id]['sub'][$service->id]['vi']['metakey'] = $service->metakey;
                $services[$service->parent_id]['sub'][$service->id]['vi']['metades'] = $service->metades;
                $services[$service->parent_id]['sub'][$service->id]['vi']['metarobot'] = $service->metarobot;

                $services[$service->parent_id]['sub'][$service->id]['en']['name'] = $service->en_name;
                $services[$service->parent_id]['sub'][$service->id]['en']['metakey'] = $service->en_metakey;
                $services[$service->parent_id]['sub'][$service->id]['en']['metades'] = $service->en_metades;
                $services[$service->parent_id]['sub'][$service->id]['en']['metarobot'] = $service->en_metarobot;
            }
        }

        $this->theme->bind('services', function() use ($services){
            return $services;
        });

        $this->theme->breadcrumb()->add($company_name, \URL::to('/'));

        $this->theme->breadcrumb()->setTemplate('
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2">
            <?php foreach ($crumbs as $i => $crumb) { ?>
                @if ($i != (count($crumbs) - 1))
                <li class="breadcrumb-item"><a href="{{ $crumb["url"] }}">{{ $crumb["label"] }}</a></li>
                @else
                <li class="breadcrumb-item active"  aria-current="page">{{ $crumb["label"] }}</li>
                @endif
            <?php } ?>
            </ul>
            </nav>
        ');

        $this->theme->bind('categories',function(){
            return $this->redisRepository->getListBlogCategory();
        });
    }
}
