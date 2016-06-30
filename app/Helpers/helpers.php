<?php

if(!function_exists('money')) {
    /**
     * @param int $amount
     * @param string $currency_code
     * @param int $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     *
     * @return string
     */
    function money($amount, $currency_code = '', $decimals = 2, $dec_point = '.', $thousands_sep = ',')
    {
        switch ($currency_code) {
            case 'USD':
            case 'AUD':
            case 'CAD':
                $currency_symbol = '$';
                break;
            case 'EUR':
                $currency_symbol = '€';
                break;
            case 'GBP':
                $currency_symbol = '£';
                break;

            default:
                $currency_symbol = '';
                break;
        }

        return $currency_symbol . number_format($amount, $decimals, $dec_point, $thousands_sep);
    }
}

if(!function_exists('category')){
    function category($category_id){
        $category_arr = array(
            0=>'首页',
            1=>'列表',
        );
        return $category_arr[$category_id];
    }
}

if(!function_exists('get_game_data')){
    function get_game_data($game_id){
        $apiUrl = 'http://app.appgame.com/api/game/';

        $appkey = "M6GHa56MJxy2S3Pc";
        $url_path = '/api/game/gamelist';

        $paramsArray['c'] = 'game';
        $paramsArray['a'] = 'gamelist';

        $paramsArray['timestamp'] = time();
        $paramsArray['page_num'] = 1;//查询页数[即查询第几页] 查内容时为0
        $paramsArray['num_pre_page'] = 50;//每页条数,默认50条

        $paramsArray['game_id'] = $game_id; //游戏id

        //签名生成串
        $strs = strtoupper('GET') . '&' . rawurlencode($url_path) . '&';
        ksort($paramsArray);
        $query_string = array();
        foreach ($paramsArray as $key => $val ){
            array_push($query_string, $key . '=' . $val);
        }
        $query_string = join('&', $query_string);
        $mk = $strs . str_replace('~', '%7E', rawurlencode($query_string));
        $my_sign = hash_hmac("sha1", $mk, $appkey, true);
        $trans = array('+' => '-', '/' => '_', '=' => '');
        $sig = strtr(base64_encode($my_sign), $trans);



        $paramsArray['sig'] = $sig;
        $query_string = array();
        foreach ($paramsArray as $key => $val ){
            array_push($query_string, $key . '=' . $val);
        }
        $query_string = join('&', $query_string);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl.'?'.$query_string);
        ob_start();
        curl_exec($ch);
        $result = ob_get_contents() ;
        ob_end_clean();

        return $result;
    }
}


