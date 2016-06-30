<?php

namespace App\Models;

class Position extends MyBaseModel
{
    /**
     * The validation rules for the model.
     *
     * @var array $rules
     */
    protected $rules = [
        'name'           => ['required'],
        'order_id'           => ['required'],
        'max_num'           => ['required'],
    ];

    /**
     * The validation error messages for the model.
     *
     * @var array $messages
     */
    protected $messages = [
        'name.required'        => 'You must at least give a name.',
        'order_id.required'        => 'You must at least give a number.',
        'max_num.required'        => 'You must at least give a number.',
    ];


    /**
     * Ensures each query looks for account_id
     *
     * @param $query
     * @param bool $accountId
     * @param Request $request
     * @return mixed
     */
//    public function scopeScope($query, $accountId = false)
//    {
//
//        /*
//         * GOD MODE - DON'T UNCOMMENT!
//         * returning $query before adding the account_id condition will let you
//         * browse all events etc. in the system.
//         * //return  $query;
//         */
//
////        if (!$accountId) {
////            $accountId = Auth::user()->account_id;
////        }
////
////        $table = $this->getTable();
////
////        $query->where(function ($query) use ($accountId, $table) {
////            $query->whereRaw(\DB::raw('('.$table.'.account_id = '.$accountId.')'));
////        });
//
//        return $query;
//    }

    public function organisers()
    {
        return $this->hasMany('\App\Models\Organiser');
    }
}
