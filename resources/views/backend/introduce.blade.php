<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="viet-tab" data-toggle="tab" href="#viet" role="tab" aria-controls="viet" aria-selected="true">Viet</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="english-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">English</a>
    </li>
    
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="viet" role="tabpanel" aria-labelledby="viet-tab">
        {!!Form::open(['route'=>'admin.introduce.save','name' => 'frm','id'=>'config-form','class' => 'form-horizontal form-bordered form-validation disable_multi_submit','enctype' => 'multipart/form-data' ]) !!}
        <div class="block">
            <div class="block-title">
              <h5><i class="fa fa-search"></i>{{ trans('form.introduce') }} </h5><hr>
            </div>
            <div class="block-form">
            	
        			<div class="form-group">
        				{{ Form::textarea('introduce',$introduce['vi']??'',['class' => 'summernote_large form-control require' ]) }}
        			</div>
        		
            </div>
        </div>
    </div>
    <div class="tab-pane fade show" id="english" role="tabpanel" aria-labelledby="english-tab">
        <div class="block">
            <div class="block-title">
              <h5><i class="fa fa-search"></i>Introduce </h5><hr>
            </div>
            <div class="block-form">
                {{ Form::textarea('en_introduce',$introduce['en']??'',['class' => 'summernote_large form-control require' ]) }}
            </div>
        </div>
    </div>
    {!! Form::submit_button() !!}
    {!! Form::close() !!}
</div>
</div>