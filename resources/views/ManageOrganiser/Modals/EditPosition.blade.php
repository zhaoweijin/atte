<div role="dialog"  class="modal fade" style="display: none;">
    {!! Form::open(array('url' => route('postEditPosition', array('organiser_id' => $organiser_id,'id' => $position->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    创建展示位</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('name', '展示位标题', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('name', $position->name,array('class'=>'form-control','placeholder'=>'E.g: 展示位标题' ))  !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('category_id', '栏目', array('class'=>'control-label')) !!}
                            {!! Form::select('category_id', ['首页','列表'], $position->category_id, ['class' => 'form-control']) !!}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('order_id', '排序', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('order_id', $position->order_id,array('class'=>'form-control','placeholder'=>'E.g: 5' ))  !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('max_num', '最大数', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('max_num', $position->max_num,array('class'=>'form-control','placeholder'=>'E.g: 5' ))  !!}
                                </div>
                            </div>
                        </div>
                    </div>



                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
                {!! Form::button('否', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('是', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
        {!! Form::close() !!}
    </div>
</div>