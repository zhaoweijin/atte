@extends('Shared.Layouts.Master')

@section('title')
    @parent
    Customize Event
@stop

@section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('page_title')
    <i class="ico-cog mr5"></i>
    Customize Event
@stop

{{--@section('page_header')--}}
    {{--<style>--}}
        {{--.page-header {--}}
            {{--display: none;--}}
        {{--}--}}
    {{--</style>--}}
{{--@stop--}}

{{--@section('head')--}}
    {{--{!! HTML::script('https://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places') !!}--}}
    {{--{!! HTML::script('vendor/geocomplete/jquery.geocomplete.min.js') !!}--}}
    {{--<script>--}}
        {{--$(function () {--}}

            {{--$("input[name='organiser_fee_percentage']").TouchSpin({--}}
                {{--min: 0,--}}
                {{--max: 100,--}}
                {{--step: 0.1,--}}
                {{--decimals: 2,--}}
                {{--verticalbuttons: true,--}}
                {{--postfix: '%',--}}
                {{--buttondown_class: "btn btn-link",--}}
                {{--buttonup_class: "btn btn-link",--}}
                {{--postfix_extraclass: "btn btn-link"--}}
            {{--});--}}
            {{--$("input[name='organiser_fee_fixed']").TouchSpin({--}}
                {{--min: 0,--}}
                {{--max: 100,--}}
                {{--step: 0.1,--}}
                {{--decimals: 2,--}}
                {{--verticalbuttons: true,--}}
                {{--postfix: '{{$event->currency->symbol_left}}',--}}
                {{--buttondown_class: "btn btn-link",--}}
                {{--buttonup_class: "btn btn-link",--}}
                {{--postfix_extraclass: "btn btn-link"--}}
            {{--});--}}

            {{----}}
            {{--$('#affiliateGenerator').on('keyup', function () {--}}
                {{--var text = $(this).val().replace(/\W/g, ''),--}}
                        {{--referralUrl = '{{$event->event_url}}?ref=' + text;--}}

                {{--$('#referralUrl').toggle(text !== '');--}}
                {{--$('#referralUrl input').val(referralUrl);--}}
            {{--});--}}

        {{--});--}}


    {{--</script>--}}

    {{--<style type="text/css">--}}
        {{--.bootstrap-touchspin-postfix {--}}
            {{--background-color: #ffffff;--}}
            {{--color: #333;--}}
            {{--border-left: none;--}}
        {{--}--}}

        {{--.bgImage {--}}
            {{--cursor: pointer;--}}
        {{--}--}}

        {{--.bgImage.selected {--}}
            {{--outline: 4px solid #0099ff;--}}
        {{--}--}}
    {{--</style>--}}
    {{--<script>--}}
        {{--$(function () {--}}

            {{--var hash = document.location.hash;--}}
            {{--var prefix = "tab_";--}}
            {{--if (hash) {--}}
                {{--$('.nav-tabs a[href=' + hash + ']').tab('show');--}}
            {{--}--}}

            {{--$(window).on('hashchange', function () {--}}
                {{--var newHash = location.hash;--}}
                {{--if (typeof newHash === undefined) {--}}
                    {{--$('.nav-tabs a[href=' + '#general' + ']').tab('show');--}}
                {{--} else {--}}
                    {{--$('.nav-tabs a[href=' + newHash + ']').tab('show');--}}
                {{--}--}}

            {{--});--}}

            {{--$('.nav-tabs a').on('shown.bs.tab', function (e) {--}}
                {{--window.location.hash = e.target.hash;--}}
            {{--});--}}

        {{--});--}}


    {{--</script>--}}

{{--@stop--}}


@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- tab -->
            <ul class="nav nav-tabs">
                <li data-route="{{route('showEventCustomizeTab', ['event_id' => $event->id, 'tab' => 'general'])}}"
                    class="{{($tab == 'general' || !$tab) ? 'active' : ''}}"><a href="#general" data-toggle="tab">General</a>
                </li>
            </ul>
            <!--/ tab -->
            <!-- tab content -->
            <div class="tab-content panel">
                <div class="tab-pane {{($tab == 'general' || !$tab) ? 'active' : ''}}" id="general">
                    @include('ManageEvent.Partials.EditEventForm', ['event'=>$event, 'organisers'=>\Auth::user()->account->organisers])
                </div>
            </div>
            <!--/ tab content -->
        </div>
    </div>
@stop