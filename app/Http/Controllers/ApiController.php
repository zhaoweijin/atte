<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends Controller
{
    /**
     * Get game data
     *
     * @param string $game_id
     * @return json
     */
    public function getGameData($game_id)
    {
        $data = json_decode(get_game_data($game_id),true);
        return $data;
    }


}
