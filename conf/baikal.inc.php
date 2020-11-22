
$prefs['{baikal_id}'] = array(
        // required attributes
        'name'         =>  '{baikal_id}',
        'username'     =>  '%u',
        'password'     =>  '%p',
        'url'          =>  '{baikal_url}/card.php/addressbooks/%u/',

        // optional attributes
        'active'       =>  true,
        'readonly'     =>  false,
        'refresh_time' => '00:05:00',

        'fixed'        =>  array('username', 'password'),
        'hide'         =>  false,
);
