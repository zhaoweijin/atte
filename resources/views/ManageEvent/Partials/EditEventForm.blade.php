@include('ManageOrganiser.Partials.EventCreateAndEditJS')

{!! Form::model($event, array('url' => route('postEditEvent', ['event_id' => $event->id]), 'class' => 'ajax gf')) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('is_live', '礼包展示', array('class'=>'control-label required')) !!}
            {!!  Form::select('is_live', [
            '1' => '是',
            '0' => '否'],null,
                                        array(
                                        'class'=>'form-control'
                                        ))  !!}
        </div>
        <div class="form-group">
            {!! Form::label('title', '礼包标题', array('class'=>'control-label required')) !!}
            {!!  Form::text('title', Input::old('title'),
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>'E.g: '.Auth::user()->first_name.'\'s Interational Conference'
                                        ))  !!}
        </div>

        <div class="form-group">
            {!! Form::label('game_id', '游戏库游戏id(获取游戏信息)', array('class'=>'control-label required')) !!}
            {!!  Form::text('game_id', Input::old('game_id'),
                                        array(
                                        'class'=>'form-control',
                                        'id'=>'game_id'
                                        ))  !!}
        </div>

        <div class="form-group">
            {!! Form::label('game', '游戏名', array('class'=>'control-label required')) !!}
            {!!  Form::text('game', Input::old('game'),
                                        array(
                                        'class'=>'form-control',
                                        'id'=>'game'
                                        ))  !!}
        </div>

        <div class="form-group">
            {!! Form::label('icon', 'ICON', array('class'=>'control-label required')) !!}
            {!!  Form::text('icon', Input::old('icon'),
                                        array(
                                        'class'=>'form-control',
                                        'id'=>'icon'
                                        ))  !!}
        </div>

        <div class="form-group">
            {!! Form::label('zone_url', '专区地址', array('class'=>'control-label')) !!}
            {!!  Form::text('zone_url', Input::old('zone_url'),
                                        array(
                                        'class'=>'form-control'
                                        ))  !!}
        </div>

        <div class="form-group">
           {!! Form::label('description', '礼包简介', array('class'=>'control-label')) !!}
            {!!  Form::textarea('description', Input::old('description'),
                                        array(
                                        'class'=>'form-control editable',
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
                    {!!  Form::text('start_date', $event->getFormattedDate('start_date'),
                                                        [
                                                    'class'=>'form-control start hasDatepicker ',
                                                    'data-field'=>'datetime',
                                                    'data-startend'=>'start',
                                                    'data-startendelem'=>'.end',
                                                    'readonly'=>''

                                                ])  !!}
                </div>
            </div>

            <div class="col-sm-6 ">
                <div class="form-group">
                    {!!  Form::label('end_date', '礼包结束时间',
                                        [
                                    'class'=>'required control-label '
                                ])  !!}
                    {!!  Form::text('end_date', $event->getFormattedDate('end_date'),
                                                [
                                            'class'=>'form-control end hasDatepicker ',
                                            'data-field'=>'datetime',
                                            'data-startend'=>'end',
                                            'data-startendelem'=>'.start',
                                            'readonly'=>''
                                        ])  !!}
                </div>
            </div>
        </div>

        {{--<div class="row">--}}
            {{--<div class="col-md-6">--}}
                {{--<div class="form-group">--}}
                   {{--{!! Form::label('event_image', 'Event Flyer', array('class'=>'control-label ')) !!}--}}
                   {{--{!! Form::styledFile('event_image', 1) !!}--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-6">--}}
                {{--<div class="float-l">--}}
                    {{--@if($event->images->count())--}}
                    {{--{!! Form::label('', 'Current Event Flyer', array('class'=>'control-label ')) !!}--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="well well-sm well-small">--}}
                           {{--{!! Form::label('remove_current_image', 'Delete?', array('class'=>'control-label ')) !!}--}}
                           {{--{!! Form::checkbox('remove_current_image') !!}--}}

                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="thumbnail">--}}
                       {{--{!!HTML::image('/'.$event->images->first()['image_path'])!!}--}}
                    {{--</div>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>

    <div class="col-md-12">
        <div class="panel-footer mt15 text-right">
           {!! Form::hidden('organiser_id', $event->organiser_id) !!}
           {!! Form::submit('Save Changes', ['class'=>"btn btn-success"]) !!}
        </div>
    </div>
    {!! Form::close() !!}
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
