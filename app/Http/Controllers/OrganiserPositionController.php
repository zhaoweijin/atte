<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\Organiser;
use Log;


class OrganiserPositionController extends MyBaseController
{
    /**
     * Show the event survey page
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function showPosition(Request $request, $organiser_id)
    {

        $organiser = Organiser::scope()->findOrfail($organiser_id);
        $allowed_sorts = ['id', 'order_id'];
        $sort_order = $request->get('sort_order') == 'asc' ? 'asc' : 'desc';
        $sort_by = (in_array($request->get('sort_by'), $allowed_sorts) ? $request->get('sort_by') : 'order_id');

        $positions = Position::where('organiser_id', '=', $organiser_id)->orderBy($sort_by, $sort_order)->get();


        $data = [
            'positions'      => $positions,
            'organiser_id'      => $organiser_id,
            'organiser'      => $organiser,
            'sort_by'    => $sort_by,
            'sort_order' => $sort_order,
            'q'          => '',
        ];

        return view('ManageOrganiser.Position', $data);
    }

    /**
     * Show the position
     *
     * @param $organiser_id
     * @return mixed
     */
    public function showCreatePosition($organiser_id){
        return view('ManageOrganiser.Modals.CreatePosition', [
            'organiser_id' => $organiser_id,
        ]);
    }

    /**
     * Show the position create
     *
     * @param Request $request
     * @param $organiser_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreatePosition(Request $request,$organiser_id){
        $position = new Position;

        if (!$position->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $position->errors(),
            ]);
        }

        $position->name = $request->get('name');
        $position->order_id = $request->get('order_id');
        $position->category_id = $request->get('category_id');
        $position->max_num = $request->get('max_num');
        $position->organiser_id = $organiser_id;
        $position->save();


        return response()->json([
            'status'      => 'success',
            'redirectUrl' => route('showPosition', [
                'organiser_id'  => $organiser_id,
                'first_run' => '',
            ]),
        ]);
    }

    /**
     * Show the edit position modal
     *
     * @param Request $request
     * @param $organiser_id
     * @return mixed
     */
    public function showEditPosition(Request $request,$organiser_id)
    {

        $position = Position::where(array('organiser_id'=>$organiser_id,'id'=>$request->id))->first();

        $data = [
            'position'  => $position,
            'organiser_id'=>$organiser_id
        ];

        return view('ManageOrganiser.Modals.EditPosition', $data);
    }


    /**
     * Edit the position
     *
     * @param Request $request
     * @param $organiser_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditPosition(Request $request, $organiser_id)
    {
        $position = Position::findOrFail($request->id);

        if (!$position->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $position->errors(),
            ]);
        }

        $position->name = $request->get('name');
        $position->order_id = $request->get('order_id');
        $position->category_id = $request->get('category_id');
        $position->max_num = $request->get('max_num');
        $position->organiser_id = $organiser_id;
        $position->save();
        return response()->json([
            'status'      => 'success',
            'message'     => 'Event Successfully Updated',
            'redirectUrl' => '',
        ]);
    }

    /**
     * Deleted a ticket
     *
     * @param Request $request
     * @param $organiser_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeletePosition(Request $request,$organiser_id)
    {
        $id = $request->get('id');

        $position = Position::find($id);

        if ($position->delete()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Position Successfully Deleted',
                'id'      => $position->id,
                'redirectUrl' => '',
            ]);
        }

        Log::error('Ticket Failed to delete', [
            'position' => $position,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $position->id,
            'message' => 'Whoops! Looks like something went wrong. Please try again.',
        ]);
    }
}
