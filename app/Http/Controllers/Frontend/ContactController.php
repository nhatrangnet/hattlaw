<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\FrontendController as FrontendController;
use Illuminate\Http\Request;
use App\Model\Contact;
use Illuminate\Support\Facades\Redis;
use app\Repository\RedisRepository as RedisRepository;
use App\Mail\ClientContact;
use App\Model\Admin;
use URL, Input, Validator, HTML, Session, DB, Theme;


class ContactController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * contact index
     * @return view
     */
    public function index()
    {
        $this->theme->set('user', $this->user_logged);
        $data = [
            'data' => 'aaa,bbb,ccc',
        ];
        // $this->theme->set('keywords','index keyword,bbbbb111');
        // $this->theme->setDescription('index description');
        $this->theme->breadcrumb()->add(trans('frontend.contact'), route('contact'));

        return $this->render('frontend.contact', $data);
    }
    public function save(Request $request)
    {
        $request->validate([
          'name' => 'required|min:1|max:255',
          'email' => 'required|email|min:1|max:255',
          'content' => 'required|min:1',
        ]);
        $input = $request->except('_token');
        if($this->repository->createContact($input))
        {
            \Mail::to(env('ADMIN_MAIL','admin@hatt.vn'))->send(new ClientContact($input));
            return redirect()->route('index')->with('success', trans('Thank you for your contact. We will contact you as soon as possible !'));
        }
        else return redirect()->route('index')->with('error', trans('An error occurred. Please contact us!'));
    }
}
