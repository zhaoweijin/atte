<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

/*
 * Logout
 */
Route::any('/api/game_data/{game_id}', [
    'uses' => 'ApiController@getGameData',
    'as'   => 'getGameData',
]);


/*
 * Logout
 */
Route::any('/logout', [
    'uses' => 'UserLogoutController@doLogout',
    'as'   => 'logout',
]);


Route::group(['middleware' => ['installed']], function () {

    /*
     * Login
     */
    Route::get('/login', [
        'as'   => 'login',
        'uses' => 'UserLoginController@showLogin',
    ]);
    Route::post('/login', 'UserLoginController@postLogin');

    /*
     * Forgot password
     */
    Route::get('login/forgot-password', [
        'as'   => 'forgotPassword',
        'uses' => 'RemindersController@getRemind',
    ]);

    Route::post('login/forgot-password', [
        'as'   => 'postForgotPassword',
        'uses' => 'RemindersController@postRemind',
    ]);

    /*
     * Reset Password
     */
    Route::get('login/reset-password/{token}', [
        'as'   => 'showResetPassword',
        'uses' => 'RemindersController@getReset',
    ]);

    Route::post('login/reset-password', [
        'as'   => 'postResetPassword',
        'uses' => 'RemindersController@postReset',
    ]);

    /*
     * Registration / Account creation
     */
    Route::get('/signup', [
        'uses' => 'UserSignupController@showSignup',
        'as'   => 'showSignup',
    ]);
    Route::post('/signup', 'UserSignupController@postSignup');

    /*
     * Confirm Email
     */
    Route::get('signup/confirm_email/{confirmation_code}', [
        'as'   => 'confirmEmail',
        'uses' => 'UserSignupController@confirmEmail',
    ]);
});

/*
 * Public organiser page routes
 */
Route::group(['prefix' => 'o'], function () {

    Route::get('/{organiser_id}/{organier_slug?}', [
        'as'   => 'showOrganiserHome',
        'uses' => 'OrganiserViewController@showOrganiserHome',
    ]);

});


/*
 * Backend routes
 */
Route::group(['middleware' => ['auth', 'first.run']], function () {

    /*
     * Edit User
     */
    Route::group(['prefix' => 'user'], function () {

        Route::get('/', [
            'as'   => 'showEditUser',
            'uses' => 'UserController@showEditUser',
        ]);
        Route::post('/', [
            'as'   => 'postEditUser',
            'uses' => 'UserController@postEditUser',
        ]);

    });


    /*
     * Manage account
     */
    Route::group(['prefix' => 'account'], function () {

        Route::get('/', [
            'as'   => 'showEditAccount',
            'uses' => 'ManageAccountController@showEditAccount',
        ]);

        Route::post('/', [
            'as'   => 'postEditAccount',
            'uses' => 'ManageAccountController@postEditAccount',
        ]);
        Route::post('/edit_payment', [
            'as'   => 'postEditAccountPayment',
            'uses' => 'ManageAccountController@postEditAccountPayment',
        ]);

        Route::post('invite_user', [
            'as'   => 'postInviteUser',
            'uses' => 'ManageAccountController@postInviteUser',
        ]);

    });

    Route::get('select_organiser', [
        'as'   => 'showSelectOrganiser',
        'uses' => 'OrganiserController@showSelectOrganiser',
    ]);

    /*
     * Organiser routes
     */
    Route::group(['prefix' => 'organiser'], function () {

        Route::get('{organiser_id}/dashboard', [
            'as'   => 'showOrganiserDashboard',
            'uses' => 'OrganiserDashboardController@showDashboard',
        ]);
        Route::get('{organiser_id}/events', [
            'as'   => 'showOrganiserEvents',
            'uses' => 'OrganiserEventsController@showEvents',
        ]);

        Route::get('{organiser_id}/customize', [
            'as'   => 'showOrganiserCustomize',
            'uses' => 'OrganiserCustomizeController@showCustomize',
        ]);
        Route::post('{organiser_id}/customize', [
            'as'   => 'postEditOrganiser',
            'uses' => 'OrganiserCustomizeController@postEditOrganiser',
        ]);

        Route::get('create', [
            'as'   => 'showCreateOrganiser',
            'uses' => 'OrganiserController@showCreateOrganiser',
        ]);
        Route::post('create', [
            'as'   => 'postCreateOrganiser',
            'uses' => 'OrganiserController@postCreateOrganiser',
        ]);

        Route::post('{organiser_id}/page_design', [
            'as'   => 'postEditOrganiserPageDesign',
            'uses' => 'OrganiserCustomizeController@postEditOrganiserPageDesign'
        ]);

        /*
         * Position
         */
        Route::get('{organiser_id}/position/', [
                'as'   => 'showPosition',
                'uses' => 'OrganiserPositionController@showPosition',
            ]
        );

        Route::get('{organiser_id}/position/create', [
                'as'   => 'showCreatePosition',
                'uses' => 'OrganiserPositionController@showCreatePosition',
            ]
        );
        Route::post('{organiser_id}/position/create', [
                'as'   => 'postCreatePosition',
                'uses' => 'OrganiserPositionController@postCreatePosition',
            ]
        );

        Route::get('{organiser_id}/position/edit', [
                'as'   => 'showEditPosition',
                'uses' => 'OrganiserPositionController@showEditPosition',
            ]
        );

        Route::post('{organiser_id}/position/{id}/edit', [
                'as'   => 'postEditPosition',
                'uses' => 'OrganiserPositionController@postEditPosition',
            ]
        );

        Route::post('{organiser_id}/position/delete', [
                'as'   => 'postDeletePosition',
                'uses' => 'OrganiserPositionController@postDeletePosition',
            ]
        );

    });

    /*
     * Events dashboard
     */
    Route::group(['prefix' => 'events'], function () {

        /*
         * ----------
         * Create Event
         * ----------
         */
        Route::get('/create', [
            'as'   => 'showCreateEvent',
            'uses' => 'EventController@showCreateEvent',
        ]);

        Route::post('/create', [
            'as'   => 'postCreateEvent',
            'uses' => 'EventController@postCreateEvent',
        ]);
    });


    /*
     * Event management routes
     */
    Route::group(['prefix' => 'event'], function () {
        /*
         * Dashboard
         */
        Route::get('{event_id}/dashboard/', [
                'as'   => 'showEventDashboard',
                'uses' => 'EventDashboardController@showDashboard',
            ]
        );

        Route::get('{event_id}', function ($event_id) {
            return Redirect::route('showEventDashboard', [
                'event_id' => $event_id,
            ]);
        });

        /*
         * @todo Move to a controller
         */
        Route::get('{event_id}/go_live', [
            'as' => 'MakeEventLive',
            function ($event_id) {
                $event = \App\Models\Event::scope()->findOrFail($event_id);
                $event->is_live = 1;
                $event->save();
                \Session::flash('message',
                    'Event Successfully Made Live! You can undo this action in event settings page.');

                return Redirect::route('showEventTickets', [
                    'event_id' => $event_id,
                ]);
            }
        ]);

        /*
         * -------
         * Tickets
         * -------
         */
        Route::get('{event_id}/tickets/', [
            'as'   => 'showEventTickets',
            'uses' => 'EventTicketsController@showTickets',
        ]);
        Route::get('{event_id}/tickets/info/', [
            'as'   => 'showInfoTicket',
            'uses' => 'EventTicketsController@showInfoTicket',
        ]);
        Route::get('{event_id}/tickets/edit/{ticket_id}', [
            'as'   => 'showEditTicket',
            'uses' => 'EventTicketsController@showEditTicket',
        ]);
        Route::post('{event_id}/tickets/edit/{ticket_id}', [
            'as'   => 'postEditTicket',
            'uses' => 'EventTicketsController@postEditTicket',
        ]);
        Route::get('{event_id}/tickets/create', [
            'as'   => 'showCreateTicket',
            'uses' => 'EventTicketsController@showCreateTicket',
        ]);
        Route::post('{event_id}/tickets/create', [
            'as'   => 'postCreateTicket',
            'uses' => 'EventTicketsController@postCreateTicket',
        ]);
        Route::post('{event_id}/tickets/delete', [
            'as'   => 'postDeleteTicket',
            'uses' => 'EventTicketsController@postDeleteTicket',
        ]);
        Route::post('{event_id}/tickets/pause', [
            'as'   => 'postPauseTicket',
            'uses' => 'EventTicketsController@postPauseTicket',
        ]);
        Route::post('{event_id}/tickets/import', [
            'as'   => 'postImportTicket',
            'uses' => 'EventTicketsController@postImportTicket',
        ]);


        /*
         * -------
         * Attendees
         * -------
         */
        Route::get('{event_id}/attendees/', [
            'as'   => 'showEventAttendees',
            'uses' => 'EventAttendeesController@showAttendees',
        ]);

        /*
         * -------
         * Orders
         * -------
         */
        Route::get('{event_id}/orders/', [
            'as'   => 'showEventOrders',
            'uses' => 'EventOrdersController@showOrders',
        ]);

        /*
         * -------
         * Edit Event
         * -------
         */
        Route::post('{event_id}/customize', [
            'as'   => 'postEditEvent',
            'uses' => 'EventController@postEditEvent',
        ]);

        /*
         * -------
         * Customize Design etc.
         * -------
         */
        Route::get('{event_id}/customize', [
            'as'   => 'showEventCustomize',
            'uses' => 'EventCustomizeController@showCustomize',
        ]);
        Route::get('{event_id}/customize/{tab?}', [
            'as'   => 'showEventCustomizeTab',
            'uses' => 'EventCustomizeController@showCustomize',
        ]);

        /*
         * -------
         * Event Widget page
         * -------
         */
        Route::get('{event_id}/widgets', [
            'as'   => 'showEventWidgets',
            'uses' => 'EventWidgetsController@showEventWidgets',
        ]);

        /*
         * -------
         * Event Survey page
         * -------
         */
        Route::get('{event_id}/surveys', [
            'as'   => 'showEventSurveys',
            'uses' => 'EventSurveyController@showEventSurveys',
        ]);

        /*
         * -------
         * Check In App
         * -------
         */
        Route::get('{event_id}/check_in', [
            'as'   => 'showChechIn',
            'uses' => 'EventCheckInController@showCheckIn',
        ]);

        /*
         * -------
         * Promote
         * -------
         */
        Route::get('{event_id}/promote', [
            'as'   => 'showEventPromote',
            'uses' => 'EventPromoteController@showPromote',
        ]);

    });
});