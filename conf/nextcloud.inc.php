
$prefs['{nextcloud_id}'] = array(
        // required attributes
        'name'         =>  '{nextcloud_id}',
        'username'     =>  '%u',
        'password'     =>  '%p',
        'url'          =>  '{nextcloud_url}/remote.php/dav/addressbooks/users/%u/contacts/',

        // optional attributes
        'active'       =>  true,
        'readonly'     =>  false,
        'refresh_time' => '00:05:00',

        'fixed'        =>  array('username', 'password'),
        'hide'         =>  false,
);
