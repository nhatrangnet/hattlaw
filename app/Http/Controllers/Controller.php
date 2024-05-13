<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Repositories\Repositories;
use App\Repositories\RedisRepository as RedisRepository;
use App\Services\Slug as SlugService;
use App\Services\ImageHelper as ImageService;
use Form;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $redisRepository;
    protected $repository;

    public function __construct(){
    	Form::component('form_text_search', 'components.form.search.text', ['name', 'value' => null, 'attributes' => []]);
    	Form::component('form_email_search', 'components.form.search.email', ['name', 'value' => null, 'attributes' => []]);
    	Form::component('form_time_search', 'components.form.search.time', ['name', 'value' => null, 'attributes' => []]);

    	Form::component('form_text', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
    	Form::component('form_email', 'components.form.email', ['name', 'value' => null, 'attributes' => []]);

        Form::component('form_select', 'components.form.select', ['name','list' => [], 'value' => null, 'attributes' => [] ] );
        Form::component('form_select_multiple', 'components.form.select_multiple', ['name','list' => [], 'value' => null, 'attributes' => []]);

        Form::component('form_password', 'components.form.password', ['name', 'attributes' => []]);
        Form::component('form_file', 'components.form.file', ['name', 'attributes' => []]);

        Form::component('form_status', 'components.form.status', ['name', 'value' => null,'options' => [], 'attributes' => []]);

    	Form::component('submit_button', 'components.form.submit', ['name', 'value' => null, 'attributes' => []]);
        Form::component('submit_back_button', 'components.form.submit_back', ['name', 'value' => null, 'attributes' => []]);
        Form::component('back_button', 'components.form.back', ['name', 'value' => null, 'attributes' => []]);


    	$this->redisRepository = new RedisRepository;
        $this->repository = new Repositories;
        $this->slugService = new SlugService;
        $this->imageService = new ImageService;
    }

    function getImageNamefromLink($link){
        $link_array = explode('/', $link);
        return end($link_array);
    }

    /**
     * Render HTML function
     * @param  theme $view
     * @param  array  $data
     * @return HTML
     */
    protected function render($view, $data = array())
    {
        return $this->theme->of($view, $data)->render();
    }

    /**
     * Render Block function
     * @param  theme $view
     * @param  array  $data
     * @return HTML
     */
    protected function renderView($view, $data = array())
    {
        return view($view, $data);
    }
}
