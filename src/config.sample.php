<?php

define('DEBUG', false);
define('LOG_ERROR_IN_DB', true);
define('LOG_INFO_IN_DB', true);

define('AUTO_DELETE_CONF_DAYS', 100);
define('JITSI_DOMAIN', 'kmeet.infomaniak.com');
define('JITSI_PREFIX', '<set a unique prefix or keep empty>');

define('DB_HOST', 'localhost');
define('DB_USER', 'jitsi_rooms');
define('DB_PASSWORD', 'jitsi_rooms');
define('DB_DATABASE', 'jitsi_rooms');

define('LOGO_URL', '');
define('FAVICON_URL', '');

define('LOCALE', 'fr');

define('LANG_MESSAGES_OVERRIDE', [
    'fr' => [
        /*
        'page_title' => 'Visio'
        'title' => 'Mon titre custom'
        
        'home_description' => <<<HTML
            
        HTML,

        'body_after' => <<<HTML
            
        HTML,
        'body_before' => <<<HTML
            
        HTML,
        'body_after_home' => <<<HTML
            
        HTML,
        'body_before_home' => <<<HTML
            
        HTML,
        */

        // ... => See lib/lang.php
    ]
]
);

define('CUSTOM_CSS', '');
