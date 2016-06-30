<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use URL;

class Event extends MyBaseModel
{
    use SoftDeletes;

    /**
     * The validation rules.
     *
     * @var array $rules
     */
    protected $rules = [
        'title'               => ['required'],
        'game'                => ['required'],
        'game_id'                => ['required'],
        'icon'                => ['required'],
//        'zone'                => ['required'],
        'description'         => ['required'],
//        'location_venue_name' => ['required_without:venue_name_full'],
//        'venue_name_full'     => ['required_without:location_venue_name'],
        'start_date'          => ['required'],
        'end_date'            => ['required'],
        'organiser_name'      => ['required_without:organiser_id'],
        'event_image'         => ['mimes:jpeg,jpg,png', 'max:3000'],
    ];

    /**
     * The validation error messages.
     *
     * @var array $messages
     */
    protected $messages = [
        'title.required'                       => 'You must at least give a title for your event.',
        'organiser_name.required_without'      => 'Please create an organiser or select an existing organiser.',
        'event_image.mimes'                    => 'Please ensure you are uploading an image (JPG, PNG, JPEG)',
        'event_image.max'                      => 'Pleae ensure the image is not larger then 3MB',
//        'location_venue_name.required_without' => 'Please enter a venue for your event',
//        'venue_name_full.required_without'     => 'Please enter a venue for your event',
    ];

    /**
     * The questions associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany('\App\Models\Question', 'event_question');
    }

    /**
     * The questions associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions_with_tashed()
    {
        return $this->belongsToMany('\App\Models\Question', 'event_question')->withTrashed();
    }

    /**
     * The attendees associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendees()
    {
        return $this->hasMany('\App\Models\Attendee');
    }

    /**
     * The images associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany('\App\Models\EventImage');
    }

    /**
     * The messages associated with the event.
     *
     * @return mixed
     */
    public function messages()
    {
        return $this->hasMany('\App\Models\Message')->orderBy('created_at', 'DESC');
    }

    /**
     * The tickets associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('\App\Models\Ticket');
    }

    /**
     * The stats associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
        return $this->hasMany('\App\Models\EventStats');
    }

    /**
     * The affiliates associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function affiliates()
    {
        return $this->hasMany('\App\Models\Affiliate');
    }

    /**
     * The orders associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('\App\Models\Order');
    }

    /**
     * The account associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('\App\Models\Account');
    }

    /**
     * The currency associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo('\App\Models\Currency');
    }

    /**
     * The organizer associated with the event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organiser()
    {
        return $this->belongsTo('\App\Models\Organiser');
    }

    /**
     * Get the embed url.
     *
     * @return mixed
     */
    public function getEmbedUrlAttribute()
    {
        return str_replace(['http:', 'https:'], '', route('showEmbeddedEventPage', ['event' => $this->id]));
    }

    /**
     * Get the fixed fee.
     *
     * @return mixed
     */
    public function getFixedFeeAttribute()
    {
        return config('attendize.ticket_booking_fee_fixed') + $this->organiser_fee_fixed;
    }

    /**
     * Get the percentage fee.
     *
     * @return mixed
     */
    public function getPercentageFeeAttribute()
    {
        return config('attendize.ticket_booking_fee_percentage') + $this->organiser_fee_percentage;
    }

    /**
     * Indicates whether the event is currently happening.
     *
     * @return bool
     */
    public function getHappeningNowAttribute()
    {
        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    /**
     * Get the currency symbol.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrencySymbolAttribute()
    {
        return $this->currency->symbol_left;
    }

    /**
     * Get the currency code.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCurrencyCodeAttribute()
    {
        return $this->currency->code;
    }

    /**
     * Get the embed html code.
     *
     * @return string
     */
    public function getEmbedHtmlCodeAttribute()
    {
        return "<!--Attendize.com Ticketing Embed Code-->
                <iframe style='overflow:hidden; min-height: 350px;' frameBorder='0' seamless='seamless' width='100%' height='100%' src='".$this->embed_url."' vspace='0' hspace='0' scrolling='auto' allowtransparency='true'></iframe>
                <!--/Attendize.com Ticketing Embed Code-->";
    }

    /**
     * Get a usable address for embedding Google Maps
     *
     */
    public function getMapAddressAttribute()
    {
        $string = $this->venue.','
            .$this->location_street_number.','
            .$this->location_address_line_1.','
            .$this->location_address_line_2.','
            .$this->location_state.','
            .$this->location_post_code.','
            .$this->location_country;

        return urlencode($string);
    }

    /**
     * Get the big image url.
     *
     * @return string
     */
    public function getBgImageUrlAttribute()
    {
        return URL::to('/').'/'.$this->bg_image_path;
    }

    /**
     * Get the url of the event.
     *
     * @return string
     */
    public function getEventUrlAttribute()
    {
        return URL::to('/').'/e/'.$this->id.'/'.Str::slug($this->title);
    }

    /**
     * Get the sales and fees volume.
     *
     * @return \Illuminate\Support\Collection|mixed|static
     */
    public function getSalesAndFeesVoulmeAttribute()
    {
        return $this->sales_volume + $this->organiser_fees_volume;
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array $dates
     */
    public function getDates()
    {
        return ['created_at', 'updated_at', 'start_date', 'end_date'];
    }
}
