<div class="container">
<div class="row">
    <div class="col-md-8 text-center">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4367901126093!2d106.69077511411653!3d10.777819862116768!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f3a172c9f7d%3A0xd13bbb5f72284d58!2zMTI2IE5ndXnhu4VuIFRo4buLIE1pbmggS2hhaSwgUGjGsOG7nW5nIDYsIFF14bqtbiAzLCBUaMOgbmggcGjhu5EgSOG7kyBDaMOtIE1pbmggNzAwMDAsIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1623656269358!5m2!1sen!2s" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
    <div class="col-md-4">
        <address><i class="fas fa-map-marked-alt red mr-1"></i><span>{{ Theme::bind('config')['company_add'] }}</span></address>
        <p><i class="fas fa-phone-square red mr-1"></i><span>{{ Theme::bind('config')['company_phone'] }}</span></p>
        <p><i class="fas fa-envelope red mr-1"></i><span><a href="mailto:{{ Theme::bind('config')['company_email'] }}?Subject=Hello" class="h6 ls1 t400">{{ Theme::bind('config')['company_email'] }}</a></span></p>
    </div>
</div>
<p class="line"></p>
<div class="row">
    <div class="col-md-8 text-center">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3898.9868850505386!2d109.1921965141776!3d12.249166591332758!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31706783d36ecbe3%3A0xe189b3bb514bfbc9!2zMTYsIDE1IFllcnNpbiwgTOG7mWMgVGjhu40sIFRow6BuaCBwaOG7kSBOaGEgVHJhbmcsIEtow6FuaCBIw7JhIDY1MDAwMCwgVmlldG5hbQ!5e0!3m2!1sen!2s!4v1592901820996!5m2!1sen!2s" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen="false" aria-hidden="false" tabindex="0"></iframe>
    </div>
    <div class="col-md-4">
        <address><i class="fas fa-map-marked-alt red mr-1"></i><span>16/15 Yersin, Nha trang City, Khanh Hoa province, Vietnam</span></address>
    </div>
</div>
<div class="row align-items-center">
    <div class="col-md-6 d-none d-md-block">
        <img src="https://i.imgur.com/pFKmUnI.jpg" class="img-fluid img-thumbnail" alt="contact-us">
    </div>
    <div class="col-md-6 mt-md-5 mb-md-5">
        <h3 class="text-center category_title">{{trans('frontend.contact_us')}}</h3><hr>
        {!!Form::open(array('url'=> route('contact.save'),'method' => 'post','name' => 'frm','id'=>'contact-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit')) !!}
                <div class="form-row">
                    <div class="col-md-6">
                        {{ Form::form_text('name','',['class' => 'form-control required' ]) }}
                    </div>
                    <div class="col-md-6">
                        {{ Form::form_email('email','',['class' => 'form-control required' ]) }}
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        {{ Form::form_text('phone','',['class' => 'form-control required' ]) }}
                    </div>
                    <div class="col-md-6">
                        {{ Form::form_text('address','',['class' => 'form-control' ]) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="content">{{ trans('form.content') }}</label>
                    {{ Form::textarea('content','',['class' => 'form-control required','rows' => 4 ]) }}
                </div>
                {!! Form::submit_button() !!}
        {!! Form::close() !!}
    </div>

</div> {{-- row --}}
</div> {{-- container --}}
