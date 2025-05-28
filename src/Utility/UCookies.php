<?php

namespace Utility;
/**
 * class to access to $_COOKIE superglobal array, You must use this class and not directly the _COOKIE array
 */
class UCookies
{
    /**
     * check if is set the specific id in the COOKIE
     * @return bool
     */
    public static function isSet($id){
        if (isset($_COOKIE[$id])){
            return true;
        } else{
            return false;
        }
    }

}