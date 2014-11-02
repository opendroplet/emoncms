<?php

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function mydroplet_controller()
{
    global $session,$route,$mysqli;

    $result = false;

    include "Modules/mydroplet/mydroplet_model.php";
    $mydroplet = new mydroplet($mysqli);

    if ($route->format == 'html')
    {
        if ($route->action == "" && $session['write']) $result = view("Modules/mydroplet/mydroplet_view.php",array());
    }

    if ($route->format == 'json')
    {
        if ($route->action == "set" && $session['write']) $result = $mydroplet->set_mysql($session['userid'],get('data'));
        if ($route->action == "get" && $session['read']) $result = $mydroplet->get_mysql($session['userid']);
    }

    return array('content'=>$result);
}

