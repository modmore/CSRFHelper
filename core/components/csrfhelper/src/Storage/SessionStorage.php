<?php

namespace modmore\CSRFHelper\Storage;

class SessionStorage implements StorageInterface {
    private static $SESSION_KEY = 'csrfhelper.csrf_tokens';
    
    public function get($key)
    {
        if (array_key_exists(self::$SESSION_KEY, $_SESSION) && array_key_exists($key, $_SESSION[self::$SESSION_KEY])) {
            return $_SESSION[self::$SESSION_KEY][$key];
        }
        return false;
    }

    public function set($key, $token)
    {
        if (!array_key_exists(self::$SESSION_KEY, $_SESSION)) {
            $_SESSION[self::$SESSION_KEY] = [];
        }

        $_SESSION[self::$SESSION_KEY][$key] = $token;
    }
}
