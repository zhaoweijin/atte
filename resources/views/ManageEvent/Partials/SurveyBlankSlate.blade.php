@extends('Shared.Layouts.BlankSlate')

@section('blankslate-icon-class')
    ico-question2
@stop

@section('blankslate-title')
    No Position Yet
@stop

@section('blankslate-text')
    Here you can add position.
@stop

@section('blankslate-body')
    <button data-invoke="modal" data-modal-id='CreateQuestion' data-href="{{route('showCreatePosition', array('organiser_id'=>$organiser_id))}}" href='javascript:void(0);'  class=' btn btn-success mt5 btn-lg' type="button" >
        <i class="ico-question"></i>
        Create Position
    </button>
@stop


