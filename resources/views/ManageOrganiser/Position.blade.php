@extends('Shared.Layouts.Master')

@section('title')
    @parent

    Event Position
@stop

@section('top_nav')
    @include('ManageOrganiser.Partials.TopNav')
@stop

@section('menu')
    @include('ManageOrganiser.Partials.Sidebar')
@stop

@section('page_title')
    <i class='ico-clipboard4 mr5'></i>
    Event Surveys
@stop

@section('head')

@stop

@section('page_header')
    <div class="col-md-9 col-sm-6">
        <!-- Toolbar -->
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group btn-group-responsive">

                <button class="loadModal btn btn-success" type="button" data-modal-id="CreateQuestion"
                        href="javascript:void(0);"
                        data-href="{{route('showCreatePosition', ['organiser_id' => $organiser_id])}}">
                    <i class="ico-question"></i> 添加展示位
                </button>
            </div>
        </div>
        <!--/ Toolbar -->
    </div>
    <div class="col-md-3 col-sm-6">

    </div>
@stop


@section('content')
    <!--Start Positions table-->
    <div class="row">
        <script>
            /*
            @todo Move this into main JS file
             */
            $(function () {


                $(document.body).on('click', '.enableQuestion', function (e) {

                    var questionId = $(this).data('id'),
                            route = $(this).data('route');

                    $.post(route, 'question_id=' + questionId)
                            .done(function (data) {

                                if (typeof data.message !== 'undefined') {
                                    showMessage(data.message);
                                }

                                switch (data.status) {
                                    case 'success':
                                        setTimeout(function () {
                                            document.location.reload();
                                        }, 300);
                                        break;
                                    case 'error':
                                        showMessage(Attendize.GenericErrorMessages);
                                        break;

                                    default:
                                        break;
                                }
                            }).fail(function (data) {
                        showMessage(Attendize.GenericErrorMessages);
                    });


                    e.preventDefault();
                });


                $('.sortable').sortable({
                    handle: '.sortHanlde',
                    forcePlaceholderSize: true,
                    placeholder: '<tr><td class="bg-info" colspan="6">&nbsp;</td></tr>'
                }).bind('sortupdate', function (e, ui) {

                    var data = $('.sortable tr').map(function () {
                        return $(this).data('question-id');
                    }).get();

                    $.ajax({
                        type: 'POST',
                        url: Attendize.postUpdateQuestionsOrderRoute,
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
        @if($positions->count())

            <div class="col-md-12">

                <!-- START panel -->
                <div class="panel">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>

                            <th>
                                展示位
                            </th>
                            <th>
                                栏目
                            </th>
                            <th>
                                最大数量
                            </th>
                            <th>
                                排序
                            </th>

                            <th>

                            </th>
                            </thead>

                            <tbody class="sortable">
                            @foreach ($positions as $position)
                                <tr id="position-{{ $position->id }}" data-question-id="{{ $position->id }}">

                                    <td>
                                        {{ $position->name }}
                                    </td>
                                    <td>
                                        {{ category($position->category_id)}}
                                    </td>
                                    <td>
                                        {{ $position->max_num}}
                                    </td>
                                    <td>
                                        {{ $position->order_id}}
                                    </td>

                                    <td class="text-center">


                                        <a class="btn btn-xs btn-primary loadModal" data-modal-id="EditPosition"
                                           href="javascript:void(0);"
                                           data-href="{{route('showEditPosition', ['organiser_id' => $organiser_id, 'id' => $position->id])}}">
                                            Edit
                                        </a>
                                        <a data-id="{{ $position->id }}"
                                           title="All answers will also be deleted. If you want to keep attendee's answers you should deactivate the question instead."
                                           data-route="{{ route('postDeletePosition', ['organiser_id' => $organiser_id, 'id' => $position->id]) }}"
                                           data-type="question" href="javascript:void(0);"
                                           class="deleteThis btn btn-xs btn-danger">
                                            Delete
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        @else

            @include('ManageEvent.Partials.SurveyBlankSlate')

        @endif
    </div>    <!--/End Positions table-->


@stop
