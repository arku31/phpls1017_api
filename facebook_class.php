<?php
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

require_once "vendor/autoload.php";

class arkufb
{
    protected $fb;
    protected $appid = '1186559161448521';

    public function __construct()
    {
        $this->fb = new \Facebook\Facebook([
            'app_id' => $this->appid,
            'app_secret' => '1661feb9e48f36d28a1107cf7e928016',
            'default_graph_version' => 'v2.2',
        ]);
    }

    public function getButtonUrl()
    {
        $helper = $this->fb->getRedirectLoginHelper();

        $permissions = ['email', 'publish_actions', 'user_birthday']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($this->getUrl(), $permissions);

        return $loginUrl;
    }

    private function getUrl()
    {
        return 'http://' . $_SERVER['SERVER_NAME'] . '/facebook.php';
    }

    public function doPostSmth()
    {
        $data = [
            'message' => 'Мой любимый кот снова на демонстрации на курсе Loftschool PHP.',
            'source' => $this->fb->fileToUpload('cat3.JPG'),
        ];
        $accessToken = $this->getAccessToken();
        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $this->fb->post('/me/photos', $data, $accessToken);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        echo 'Photo ID: ' . $graphNode['id'];
    }

    private function getAccessToken()
    {
        $helper = $this->fb->getRedirectLoginHelper();

        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }
        try {
            $accessToken = $helper->getAccessToken($this->getUrl()); //КРОМЕ ЭТОЙ СТРОКИ МОЖНО ВСЕ УДАЛИТЬ
        } catch (FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
            } else {
                header('HTTP/1.0 400 Bad Request');
            }
            exit;
        }

// The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this->fb->getOAuth2Client();

// Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($this->appid);
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (!$accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (FacebookSDKException $e) {
                exit;
            }
        }
        return $accessToken;
    }

    public function doLogin()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->fb->get(
            '/me?fields=id,first_name,email,gender,last_name,picture.width(600),birthday',
            $accessToken->getValue()
        );
        $user = $response->getGraphUser();

        echo "<pre>";
        print_r($response);
        die();
    }

}

/*
 * Структура БД, таблица users
 * id обычный ид
 * email обычный емейл
 * password обычный зашифрованный пароль
 * fb_id NULL
 *
 * ----- продолжение строки 118
 * email - $user->email
 * name, lastname - если нужен
 * password - генерируем САМИ и высылаем юзеру на почту
 * fb_id = записываем, $user->id
 * Auth::loginUsingId($currentuser->id)
 * -- производим авторизацию (логин), человек не авторизован, он нажимает кнопку войти через фейсбук
 * select * from users where fb_id = $user->id
 */