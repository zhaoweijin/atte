<div role="dialog"  class="modal fade " style="display: none;">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    礼包详情</h3>
            </div>
            <div class="modal-body">
                {{-- */$i=1;/* --}}
                <ul class="list-group">
                @foreach($ticket as $val)
                    <li class="list-group-item">{{$val->card}}</li>
                {{--<div class="input-group">--}}
                    {{--<span class="input-group-addon">{{$i++}}</span>--}}
                    {{--<input type="text" class="form-control" value="{{$val->card}}" aria-describedby="basic-addon1">--}}
                {{--</div>--}}
                @endforeach
                </ul>
            </div> <!-- /end modal body-->

        </div><!-- /end modal content-->

    </div>
</div>
