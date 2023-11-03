<?php
$cookieParams = session_get_cookie_params();
$cookieParams['secure'] = true;
$cookieParams['httponly'] = true;
session_set_cookie_params($cookieParams);
session_start();
