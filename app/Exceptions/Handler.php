<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Theme;
use App\Repositories\RedisRepository as RedisRepository;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
    protected $theme;
    protected $redisRepository;

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if(strpos($request->getRequestUri(), 'dashboard') == false){ //frontend
            $this->redisRepository = new RedisRepository;
            // try {
                $this->theme = Theme::uses(env('APP_THEME', 'default'))->layout('layout');
            // }
            // catch (\Exception $e){
            //     throw new TemplateNotFound;
            // }
            $this->theme->asset()->add('default-style', 'css/app.css');
            $this->theme->asset()->add('frontend-style', 'css/frontend.css');

            $this->theme->asset()->add('default-script', 'js/app.js');
            $this->theme->asset()->add('datatable-script', 'js/datatable.js');
            $this->theme->asset()->container('footer')->add('frontend-script', 'js/frontend.js');

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
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                switch($exception->getStatusCode()){
                    case 401: $view = '401';break; //not authorize
                    case 403: $view = '403';break; //access denied
                    case 404: $view = '404';break; // URL not found
                    case 500: $view = '500';break;
                };
                
                return $this->theme->of('errors.'.$view)->render();
            }
        }
        return parent::render($request, $exception);
    }
}
