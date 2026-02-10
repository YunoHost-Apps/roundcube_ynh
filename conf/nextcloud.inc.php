
$prefs['{nextcloud_id}'] = array(
        // required attributes
        'accountname'         =>  '{nextcloud_id} addressbooks',
        'username'     =>  '%u',
        'password'     =>  '%p',
        'discovery_url'          =>  '{nextcloud_url}/remote.php/dav/addressbooks/users/%u/',
        'rediscover_time' => '12:09:00',

        // optional attributes
        'active'       =>  true,
        'readonly'     =>  false,
        'refresh_time' => '00:05:00',

        'fixed'        =>  array('username', 'password'),
        'hide'         =>  false,
);
