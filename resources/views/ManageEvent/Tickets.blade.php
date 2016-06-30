@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Event 礼包
@stop

@section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop

@section('page_title')
    <i class="ico-ticket mr5"></i>
    礼包码
@stop


@section('head')
    <script>
        $(function () {


            $('.sortable').sortable({
                handle: '.sortHanlde',
                forcePlaceholderSize: true,
                placeholderClass: 'col-md-4 col-sm-6 col-xs-12',
            }).bind('sortupdate', function (e, ui) {

                var data = $('.sortable tr').map(function () {
                    return $(this).data('question-id');
                }).get();

                $.ajax({
                    type: 'POST',
                    url: '',//Attendize.postUpdateTicketsOrderRoute,
                    dataType: 'json',
                    data: {question_ids: data},
                    success: function (data) {
                        showMessage(data.message)
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
    @stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <!-- Toolbar -->
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-responsive">
                <button data-modal-id='CreateTicket'
                        data-href="{{route('showCreateTicket', array('event_id'=>$event->id))}}"
                        class='loadModal btn btn-success' type="button"><i class="ico-ticket"></i> 导入数据
                </button>
            </div>

            @if(false)
                <div class="btn-group btn-group-responsive ">
                    <button data-modal-id='TicketQuestions'
                            data-href="{{route('showTicketQuestions', array('event_id'=>$event->id))}}" type="button"
                            class="loadModal btn btn-success">
                        <i class="ico-question"></i> Questions
                    </button>
                </div>
                <div class="btn-group btn-group-responsive">
                    <button type="button" class="btn btn-success">
                        <i class="ico-tags"></i> Coupon Codes
                    </button>
                </div>
            @endif
        </div>
        <!--/ Toolbar -->
    </div>
    {{--<div class="col-md-3">
        {!! Form::open(array('url' => route('showEventTickets', ['event_id'=>$event->id,'sort_by'=>$sort_by]), 'method' => 'get')) !!}
        <div class="input-group">
            <input name='q' value="{{$q or ''}}" placeholder="Search Tickets.." type="text" class="form-control">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
            {!!Form::hidden('sort_by', $sort_by)!!}
        </div>
        {!! Form::close() !!}
    </div>--}}
@stop

@section('content')

                <!--Start ticket table-->
        <div class="row sortable">
            @if($tickets->count())


                <div id="ticket_" class="col-md-4 col-sm-6 col-xs-12 ">
                    <div class="panel panel-success ticket">

                        <div style="cursor: pointer;" data-modal-id='ticket-'
                             data-href="{{ route('showInfoTicket', ['event_id' => $event->id]) }}"
                             class="panel-heading loadModal">
                            <h3 class="panel-title">
                                <i class="ico-ticket ticket_icon mr5 ellipsis"></i>
                                {{$event->title}}
                                <span class="pull-right">
                                    "FREE"
                                </span>
                            </h3>
                        </div>

                        <div class='panel-body'>
                            <ul class="nav nav-section nav-justified mt5 mb5">
                                <li>
                                    <div class="section">
                                        <h4 class="nm">{{ $cost }}</h4>

                                        <p class="nm text-muted">Sold</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="section">
                                        <h4 class="nm">
                                            {{ ($tickets->count())-$cost }}
                                        </h4>

                                        <p class="nm text-muted">Remaining</p>
                                    </div>
                                </li>

                            </ul>
                        </div>
                        {{--<div class="panel-footer sortHandle" style="height: 56px;">
                            <ul class="nav nav-section nav-justified">
                                <li>
                                    <a href="javascript:void(0);">
                                        @if($ticket->sale_status === config('attendize.ticket_status_on_sale'))
                                            @if($ticket->is_paused)
                                                Ticket Sales Paused &nbsp;
                                                <span class="pauseTicketSales label label-info"
                                                      data-id="{{$ticket->id}}"
                                                      data-route="{{route('postPauseTicket', ['event_id'=>$event->id])}}">
                                <i class="ico-play4"></i> Resume
                            </span>
                                            @else
                                                On Sale &nbsp;
                                                <span class="pauseTicketSales label label-info"
                                                      data-id="{{$ticket->id}}"
                                                      data-route="{{route('postPauseTicket', ['event_id'=>$event->id])}}">
                                <i class="ico-pause"></i> Pause
                            </span>
                                            @endif
                                        @else
                                            {{\App\Models\TicketStatus::find($ticket->sale_status)->name}}
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>--}}
                    </div>
                </div>


            @else

                @if($q)
                    @include('Shared.Partials.NoSearchResults')
                @else
                    @include('ManageEvent.Partials.TicketsBlankSlate')
                @endif


            @endif
        </div><!--/ end ticket table-->

        {{--<div class="row">
            <div class="col-md-12">
                {!! $tickets->appends(['q' => $q, 'sort_by' => $sort_by])->render() !!}
            </div>
        </div>--}}
@stop

