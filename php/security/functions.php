<?php 
    function regexCheck($type, $check) {
        switch($type) {
            case 'password':
                return preg_match('/^.{8,}$/', $check);
                // this one is for password minimum length = 8 and atleast 4 a-Z and 1 special character
            case 'email':
                return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $check);
                // this one is for emails
            case 'username':
                return preg_match('/^[a-zA-Z]{4,}$/', $check);
                // use this one for usernames that cant contain numbers just a-Z
            case 'dob':
                return preg_match('/^(19[2-9]\d|20\d{2})-(0?[1-9]|1[0-2])-(0?[1-9]|[12]\d|3[01])$/', $check);
                // use this one for date of births YYYY-MM-DD is the format
            case 'name':
                return preg_match('/^[\p{L}\p{Ll}\p{Lu}]+$/u', $check);
                // use this one for names
        }
    }

    function error($num) {
        return json_encode(array('error' => $num));
    }
