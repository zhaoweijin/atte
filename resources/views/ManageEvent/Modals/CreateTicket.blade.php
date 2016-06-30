<div role="dialog"  class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postImportTicket', array('event_id' => $event->id)),'files' => true, 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    导入数据</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('ticket_id', '类型', array('class'=>'control-label required')) !!}
                                    {!! Form::select('ticket_id', ['发号'], 0, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <!-- Import -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::labelWithHelp('ticket_list', '导入文件', array('class'=>'control-label required'),
                                        '文件必须是.csv而且第一列是礼包码(换行隔开)') !!}
                                    {!!  Form::styledFile('ticket_list',1,array('id'=>'input-ticket_list'))  !!}
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