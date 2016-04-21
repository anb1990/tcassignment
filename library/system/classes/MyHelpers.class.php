<?php

class MyHelpers {

    /**
     * Gets server url path
     */
    public static function getServerUrl() {
        $port = $_SERVER['SERVER_PORT'];
        $http = "http";

        if ($port == "80") {
            $port = "";
        }

        if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $http = "https";
        }
        if (empty($port)) {
            return $http . "://" . $_SERVER['SERVER_NAME'];
        } else {
            return $http . "://" . $_SERVER['SERVER_NAME'] . ":" . $port;
        }
    }

    /**
     * Check register globals and remove them
     */
    public static function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /** Check for Magic Quotes and remove them * */
    public static function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map(self::stripSlashesDeep, $value) : stripslashes($value);
        return $value;
    }

    /**
     * Check for Magic Quotes and remove them
     */
    public static function removeMagicQuotes() {
        if (get_magic_quotes_gpc()) {
            if (isset($_GET)) {
                $_GET = self::stripSlashesDeep($_GET);
            }

            if (isset($_POST)) {
                $_POST = self::stripSlashesDeep($_POST);
            }

            if (isset($_COOKIE)) {
                $_COOKIE = self::stripSlashesDeep($_COOKIE);
            }
        }
    }

    public static function isLocalhost() {
        // if this is localhost
        return $_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1';
    }

    /**
     * Check if the action is AJAX request
     * 
     */
    public static function isAjax() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));
    }

    /**
     * startsWith
     * 
     * @param mixed $haystack the source content
     * @param mixed $needle the string to search
     */
    public static function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * endsWith
     * 
     * @param mixed $haystack the source content
     * @param mixed $needle the string to search
     */
    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start = $length * -1; //negative
        return (substr($haystack, $start) === $needle);
    }

    /**
     * trim start character
     */
    public static function trimStart($prefix, $string) {

        if (substr($string, 0, strlen($prefix)) == $prefix) {
            $string = substr($string, strlen($prefix), strlen($string));
        }

        return $string;
    }

    /**
     * trim end character
     */
    public static function trimEnd($suffix, $string) {
        if (substr($string, (strlen($suffix) * -1)) == $suffix) {
            $string = substr($string, 0, strlen($string) - strlen($suffix));
        }

        return $string;
    }

    /**
     * resolves a virtual path into an absolute path
     */
    public static function UrlContent($path, $sub = 'application') {
        if (self::startsWith($path, '~')) {
            $path = str_replace('/', '/', $path);
            $appPath = ROOT . '/' . $sub . (self::startsWith($path, '~/') ? '' : '/');
            $result = str_replace('~', $appPath, $path);
            $result = str_replace('/' . '/', '/', $result);
            //die($result);
            return $result;
        } else {
            return $path;
        }
    }

}
