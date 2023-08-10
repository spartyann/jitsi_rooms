<?php
require_once('lib/app.php');

function response($data)
{
    header("Content-Type: application/json");
    echo json_encode($data);
    exit();
}

function processApi()
{
    $data = json_decode(file_get_contents("php://input"), true);

    $action = $data['action'] ?? '';
    $confCode = $data['conf_code'] ?? '';
    $confAdminPassword = $data['conf_admin_password'] ?? '';
    $newConfAdminPassword = $data['new_conf_admin_password'] ?? '';
    

    if ($action == 'getOrCreate')
    {
        $conf = Data::getOrCreateConf($confCode, $confAdminPassword);

        Data::refreshConf($confCode);

        $isAdmin = $conf['admin_password'] == $confAdminPassword;

        // Mask password
        if ($isAdmin == false) $conf['admin_password'] = '';

        return[
            'conf' => $conf,
            'isAdmin' => $isAdmin
        ];
    }
    
    
    if ($action == 'updateConf')
    {
        $rooms = json_decode($data['rooms'] ?? 'null');
        if ($rooms == null || is_array($rooms) == false) apiError('Invalid Rooms api param'); 
        foreach($rooms as $room) {
            if (is_string($room) == false) apiError('Invalid Rooms api param'); 
            if (preg_match("/^[a-zA-Z0-9]( |[a-zA-Z0-9_-])*$/", $room) !== 1) apiError('Invalid Rooms api param'); 
        }

        $conf = Data::getOrCreateConf($confCode);

        // Check ACL
        if ($conf['admin_password'] != $confAdminPassword) return apiError(Lang::_('invalid_password'), 403);
    
        $conf = Data::createOrUpdateConf($confCode, $newConfAdminPassword, [
            'rooms' => $rooms
        ]);
    
        Data::refreshConf($confCode);
    
        return [
            'conf' => $conf
        ];
    }
}

response(processApi());

