<div role="dialog"  class="modal fade" style="display: none;">

    @include('ManageOrganiser.Partials.EventCreateAndEditJS');

    {!! Form::open(array('url' => route('postCreateEvent'), 'class' => 'ajax gf')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-calendar"></i>
                    Create Event</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', '礼包标题', array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', Input::old('title'),array('class'=>'form-control','placeholder'=>'E.g: 礼包标题' ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('game_id', '游戏库游戏id(获取游戏信息)', array('class'=>'control-label required')) !!}
                            {!!  Form::text('game_id', Input::old('game_id'),array('class'=>'form-control' ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('game', '游戏名', array('class'=>'control-label required')) !!}
                            {!!  Form::text('game', Input::old('game'),array('class'=>'form-control','placeholder'=>'E.g: 游戏名' ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('icon', 'ICON', array('class'=>'control-label required')) !!}
                            {!!  Form::text('icon', Input::old('icon'),array('class'=>'form-control','placeholder'=>'E.g: http://appgame.com/1.jpg' ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('zone', '专区地址', array('class'=>'control-label')) !!}
                            {!!  Form::text('zone', Input::old('zone'),array('class'=>'form-control','placeholder'=>'E.g: http://appgame.com/1.html(专区地址)' ))  !!}
                        </div>

                        <div class="form-group custom-theme">
                            {!! Form::label('description', '礼包简介', array('class'=>'control-label required')) !!}
                            {!!  Form::textarea('description', Input::old('description'),
                                        array(
                                        'class'=>'form-control  editable',
                                        'rows' => 5
                                        ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('type', '类型', array('class'=>'control-label required')) !!}
                            {!!  Form::select('type', [
                            '1' => '微信',
                            '0' => 'wap'],0,
                                                        array(
                                                        'class'=>'form-control'
                                                        ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('position', '推荐位', array('class'=>'control-label required')) !!}
                            {!!  Form::select('position', [
                            '1' => '首页',
                            '0' => '空'],0,
                                                        array(
                                                        'class'=>'form-control'
                                                        ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('hot', '热门', array('class'=>'control-label required')) !!}
                            {!!  Form::select('hot', [
                            '1' => '是',
                            '0' => '否'],null,
                                                        array(
                                                        'class'=>'form-control'
                                                        ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('is_tao', '淘号', array('class'=>'control-label required')) !!}
                            {!!  Form::select('is_tao', [
                            '1' => '开启',
                            '0' => '关闭'],null,
                                                        array(
                                                        'class'=>'form-control'
                                                        ))  !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_date', '礼包开始时间', array('class'=>'required control-label')) !!}
                                    {!!  Form::text('start_date', Input::old('start_date'),
                                                        [
                                                    'class'=>'form-control start hasDatepicker ',
                                                    'data-field'=>'datetime',
                                                    'data-startend'=>'start',
                                                    'data-startendelem'=>'.end',
                                                    'readonly'=>''

                                                ])  !!}
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!!  Form::label('end_date', '礼包结束时间',
                                                [
                                            'class'=>'required control-label '
                                        ])  !!}

                                    {!!  Form::text('end_date', Input::old('end_date'),
                                                [
                                            'class'=>'form-control end hasDatepicker ',
                                            'data-field'=>'datetime',
                                            'data-startend'=>'end',
                                            'data-startendelem'=>'.start',
                                            'readonly'=> ''
                                        ])  !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('type', 'Type', array('class'=>'control-label required')) !!}
                            {!! Form::select('type', ['Iso','Android','other'], 0, ['class' => 'form-control']) !!}
                        </div>

                        @if($organiser_id)
                            {!! Form::hidden('organiser_id', $organiser_id) !!}
                        @else
                            <div class="create_organiser" style="{{$organisers->isEmpty() ? '' : 'display:none;'}}">
                                <h5>
                                    Organiser Details
                                </h5>

                                <div class="form-group">
                                    {!! Form::label('organiser_name', 'Organiser Name', array('class'=>'required control-label ')) !!}
                                    {!!  Form::text('organiser_name', Input::old('organiser_name'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>'Who\'s organising the event?'
                                                ))  !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('organiser_email', 'Organiser Email', array('class'=>'control-label required')) !!}
                                    {!!  Form::text('organiser_email', Input::old('organiser_email'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>''
                                                ))  !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('organiser_about', 'Organizer Description', array('class'=>'control-label ')) !!}
                                    {!!  Form::textarea('organiser_about', Input::old('organiser_about'),
                                                array(
                                                'class'=>'form-control editable2',
                                                'placeholder'=>'',
                                                'rows' => 4
                                                ))  !!}
                                </div>
                                <div class="form-group more-options">
                                    {!! Form::label('organiser_logo', 'Organiser Logo', array('class'=>'control-label ')) !!}
                                    {!! Form::styledFile('organiser_logo') !!}
                                </div>
                                <div class="row more-options">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('organiser_facebook', 'Organiser Facebook', array('class'=>'control-label ')) !!}
                                            {!!  Form::text('organiser_facebook', Input::old('organiser_facebook'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>'E.g http://www.facebook.com/MyFaceBookPage'
                                                ))  !!}

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::label('organiser_twitter', 'Organiser Twitter', array('class'=>'control-label ')) !!}
                                            {!!  Form::text('organiser_twitter', Input::old('organiser_twitter'),
                                                array(
                                                'class'=>'form-control ',
                                                'placeholder'=>'E.g http://www.twitter.com/MyTwitterPage'
                                                ))  !!}

                                        </div>
                                    </div>
                                </div>

                                <a data-show-less-text="Hide Additional Oraganiser Options" href="javascript:void(0);"
                                   class="in-form-link show-more-options">
                                    Additional Organiser Options
                                </a>
                            </div>

                            @if(!$organisers->isEmpty())
                                <div class="form-group select_organiser" style="{{$organisers ? '' : 'display:none;'}}">

                                    {!! Form::label('organiser_id', 'Select Organiser', array('class'=>'control-label ')) !!}
                                    {!! Form::select('organiser_id', $organisers, $organiser_id, ['class' => 'form-control']) !!}

                                </div>
                                <span class="">
                                    <a data-toggle-class=".select_organiser, .create_organiser"
                                       data-show-less-text="or <b>Select an organiser</b>" href="javascript:void(0);"
                                       class="in-form-link show-more-options">
                                        or <b>Create an organiser</b>
                                    </a>
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="uploadProgress"></span>
                {!! Form::button('Cancel', ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('Create Event', ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<script>
    $(function () {
        $("#game_id").blur(function(){
            var num = $(this).val(),
                    url = "{{route('getGameData', ['game_id' => ''])}}";
            url += "/"+num;
            $.ajax({ url: url, success: function(data){
                if(data.data.result.length>0){
                    var game = data.data.result[0].game_name,
                            icon = data.data.result[0].game_icon,
                            zone_url = data.data.result[0].game_zone;
                    $('#game').val(game);
                    $('#icon').val(icon);
                    $('#zone_url').val(zone_url);
                }else{
                    alert('数据库没有该游戏');
                }
            }});
        });
    });
</script>
