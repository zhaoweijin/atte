<div class="panel panel-success event">
    <div class="panel-heading" data-style="background-color: {{{$event->bg_color}}};background-image: url({{{$event->bg_image_url}}}); background-size: cover;">
        <div class="event-date">
            <div class="month">
                {{strtoupper($event->start_date->format('M'))}}
            </div>
            <div class="day">
                {{$event->start_date->format('d')}}
            </div>
        </div>
        <ul class="event-meta">
            <li class="event-title">
                <a title="{{{$event->title}}}" href="{{route('showEventDashboard', ['event_id'=>$event->id])}}">
                    {{{ str_limit($event->title, $limit = 75, $end = '...') }}}
                </a>
            </li>
            <li class="event-organiser">
                By <a href='{{route('showOrganiserDashboard', ['organiser_id' => $event->organiser->id])}}'>{{{$event->organiser->name}}}</a>
            </li>
        </ul>

    </div>

    <div class="panel-body">
        <ul class="nav nav-section nav-justified mt5 mb5">
            <li>
                <div class="section">
                    <h4 class="nm">{{$event->tickets->where('state', '=', 1)->count()}}</h4>
                    <p class="nm text-muted">已领取</p>
                </div>
            </li>

            <li>
                <div class="section">
                    <h4 class="nm">{{$event->tickets->count()}}</h4>
                    <p class="nm text-muted">总数</p>
                </div>
            </li>
            <li>
                <div class="section">
                    <h4 class="nm"></h4>
                    <p class="nm text-muted">微信</p>
                </div>
            </li>
        </ul>
    </div>
    <div class="panel-footer">
        <ul class="nav nav-section nav-justified">
            <li>
                <a href="{{route('showEventCustomize', ['event_id' => $event->id])}}">
                    <i class="ico-edit"></i> Edit
                </a>
            </li>

            <li>
                <a href="{{route('showEventTickets', ['event_id' => $event->id])}}">
                    <i class="ico-cog"></i> Manage
                </a>
            </li>
        </ul>
    </div>
</div>