<?php

namespace arku;

class Vk
{
    protected $appid = 6273103;
    protected $protectedkey = "6NOMtWdZmxU7ntCuCgrg"; //секретный ключ, secret
    protected $url = "http://api.loft:85/vk.php";

    public function simplevk()
    {
        $url = 'https://api.vk.com/method/users.get?user_ids=2&fields=bdate&v=5.68';

        echo file_get_contents($url);
    }

    public function authorizeUrl()
    {
        $auth = "https://oauth.vk.com/authorize?client_id={$this->appid}&client_secret={$this->protectedkey}";
        $auth.= "&v=5.63&response_type=code&redirect_uri={$this->url}&scope=email";
        return $auth;

    }

    public function access_token($code)
    {
        $params = http_build_query([
            'client_id' => $this->appid,
            'client_secret' => $this->protectedkey,
            'redirect_uri' => $this->url,
            'code' => $code
        ]);
        $url = "https://oauth.vk.com/access_token?".$params;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data['access_token'];
    }

    public function vkwithtoken($token)
    {
        $url= 'https://api.vk.com/method/board.getComments?group_id=76297300&topic_id=32368710'.
            '&access_token='.$token;
//        $url='https://api.vk.com/method/users.get?user_ids=5101016&access_token='.$token.'&fields=photo_50,online';
        $result= file_get_contents($url);
//        $data = json_decode($result, true)['response']['comments'];
        $data = json_decode($result, true);
       return $data;
    }

}


/*
 * Таблица users
 * id 255
 * email asd@asd.ru
 * name
 * surname
 * password adasdasd
 * vk_id 5123
 * fb_id 1111111
 *
 * select * from users where vk_id = 5123;
 */