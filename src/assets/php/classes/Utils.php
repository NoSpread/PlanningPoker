<?php

class Utils
{

    /**
     * get ip address
     * @access public
     * @return string $ip_address
     */
    public static function get_ip()
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        $ip_address = $ip;
                    }
                }
            }
        }
        if (!isset($ip_address))
            $ip_address = '';

        return $ip_address;
    }

    /**
     * redirect to transferred url
     * @access public
     * @param mixed $url
     * @return void
     */
    public static function redirect($url)
    {
        header("Location:" . $url);
        exit;
    }

     /**
     * Get current URL info
     * @access public
     * @param string path|parse
     * @return object
     */
    public static function getCurrentUrl($type = "path")
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        switch($type) {
            case "path":
                return pathinfo($actual_link);
                break;
            case "parse":
                return parse_url($actual_link);
            break;
            default:
                return null;
        }
    }

    /**
     * generates token
     * @access public
     * @param int $length
     * @return object
     */
    public static function generateToken($length = 20)
    {
        return bin2hex(random_bytes($length));
    }
}
