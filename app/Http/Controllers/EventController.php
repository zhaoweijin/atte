<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventImage;
use App\Models\Organiser;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Image;
use Validator;
use Log;

class EventController extends MyBaseController
{
    /**
     * Show the 'Create Event' Modal
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showCreateEvent(Request $request)
    {
        $data = [
            'modal_id'     => $request->get('modal_id'),
            'organisers'   => Organiser::scope()->lists('name', 'id'),
            'organiser_id' => $request->get('organiser_id') ? $request->get('organiser_id') : false,
        ];

        return view('ManageOrganiser.Modals.CreateEvent', $data);
    }

    /**
     * Create an event
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateEvent(Request $request)
    {
        $event = Event::createNew();

        if (!$event->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $event->errors(),
            ]);
        }

        $event->title = $request->get('title');
        $event->description = strip_tags($request->get('description'));
        $event->game = $request->get('game');
        $event->game_id = $request->get('game_id');
        $event->icon = $request->get('icon');
        $event->type = $request->get('type');
        $event->position = $request->get('position');
        $event->hot = $request->get('hot');
        $event->is_tao = $request->get('is_tao');
        $event->start_date = $request->get('start_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('start_date')) : null;

        /*
         * Venue location info (Usually auto-filled from google maps)
         */

//        $is_auto_address = (trim($request->get('place_id')) !== '');
//
//        if ($is_auto_address) { /* Google auto filled */
//            $event->venue_name = $request->get('name');
//            $event->venue_name_full = $request->get('venue_name_full');
//            $event->location_lat = $request->get('lat');
//            $event->location_long = $request->get('lng');
//            $event->location_address = $request->get('formatted_address');
//            $event->location_country = $request->get('country');
//            $event->location_country_code = $request->get('country_short');
//            $event->location_state = $request->get('administrative_area_level_1');
//            $event->location_address_line_1 = $request->get('route');
//            $event->location_address_line_2 = $request->get('locality');
//            $event->location_post_code = $request->get('postal_code');
//            $event->location_street_number = $request->get('street_number');
//            $event->location_google_place_id = $request->get('place_id');
//            $event->location_is_manual = 0;
//        } else { /* Manually entered */
//            $event->venue_name = $request->get('location_venue_name');
//            $event->location_address_line_1 = $request->get('location_address_line_1');
//            $event->location_address_line_2 = $request->get('location_address_line_2');
//            $event->location_state = $request->get('location_state');
//            $event->location_post_code = $request->get('location_post_code');
//            $event->location_is_manual = 1;
//        }

        $event->end_date = $request->get('end_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('end_date')) : null;

//        $event->currency_id = Auth::user()->account->currency_id;
        //$event->timezone_id = Auth::user()->account->timezone_id;

        if ($request->get('organiser_name')) {
            $organiser = Organiser::createNew(false, false, true);

            $rules = [
                'organiser_name'  => ['required'],
                'organiser_email' => ['required', 'email'],
            ];
            $messages = [
                'organiser_name.required' => 'You must give a name for the event organiser.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => 'error',
                    'messages' => $validator->messages()->toArray(),
                ]);
            }

            $organiser->name = $request->get('organiser_name');
            $organiser->about = $request->get('organiser_about');
            $organiser->email = $request->get('organiser_email');
            $organiser->facebook = $request->get('organiser_facebook');
            $organiser->twitter = $request->get('organiser_twitter');
            $organiser->save();
            $event->organiser_id = $organiser->id;

        } elseif ($request->get('organiser_id')) {
            $event->organiser_id = $request->get('organiser_id');
        } else { /* Somethings gone horribly wrong */
            return response()->json([
                'status'   => 'error',
                'messages' => 'There was an issue finding the organiser.',
            ]);
        }

        try {
            $event->save();
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status'   => 'error',
                'messages' => 'Whoops! There was a problem creating your event. Please try again.',
            ]);
        }

        if ($request->hasFile('event_image')) {
            $path = public_path().'/'.config('attendize.event_images_path');
            $filename = 'event_image-'.md5(time().$event->id).'.'.strtolower($request->file('event_image')->getClientOriginalExtension());

            $file_full_path = $path.'/'.$filename;

            $request->file('event_image')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            /* Upload to s3 */
            \Storage::put(config('attendize.event_images_path').'/'.$filename, file_get_contents($file_full_path));

            $eventImage = EventImage::createNew();
            $eventImage->image_path = config('attendize.event_images_path').'/'.$filename;
            $eventImage->event_id = $event->id;
            $eventImage->save();
        }

        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,
            'redirectUrl' => route('showEventTickets', [
                'event_id'  => $event->id,
                'first_run' => 'yup',
            ]),
        ]);
    }

    /**
     * Edit an event
     *
     * @param Request $request
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditEvent(Request $request, $event_id)
    {
        $event = Event::scope()->findOrFail($event_id);

        if (!$event->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $event->errors(),
            ]);
        }

        $event->is_live = $request->get('is_live');
        $event->title = $request->get('title');
        $event->game = $request->get('game');
        $event->game_id = $request->get('game_id');
        $event->icon = $request->get('icon');
        $event->type = $request->get('type');
        $event->position = $request->get('position');
        $event->hot = $request->get('hot');
        $event->is_tao = $request->get('is_tao');
        $event->description = strip_tags($request->get('description'));
        $event->start_date = $request->get('start_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('start_date')) : null;
        $event->end_date = $request->get('end_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('end_date')) : null;
        $event->save();

        return response()->json([
            'status'      => 'success',
            'id'          => $event->id,
            'message'     => 'Event Successfully Updated',
            'redirectUrl' => '',
        ]);
    }

    /**
     * Upload event image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postUploadEventImage(Request $request)
    {
        if ($request->hasFile('event_image')) {
            $the_file = \File::get($request->file('event_image')->getRealPath());
            $file_name = 'event_details_image-'.md5(microtime()).'.'.strtolower($request->file('event_image')->getClientOriginalExtension());

            $relative_path_to_file = config('attendize.event_images_path').'/'.$file_name;
            $full_path_to_file = public_path().'/'.$relative_path_to_file;

            $img = Image::make($the_file);

            $img->resize(1000, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($full_path_to_file);
            if (\Storage::put($file_name, $the_file)) {
                return response()->json([
                    'link' => '/'.$relative_path_to_file,
                ]);
            }

            return response()->json([
                'error' => 'There was a problem uploading your image.',
            ]);
        }
    }
}
