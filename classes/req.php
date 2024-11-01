<?php
class reqWns {
    static protected $_requestData;
    static protected $_requestMethod;

    static public function init() {
		// Empty for now
    }
	static public function startSession() {
		if(!utilsWns::isSessionStarted()) {
			session_start();
		}
	}
/**
 * @param string $name key in variables array
 * @param string $from from where get result = "all", "input", "get"
 * @param mixed $default default value - will be returned if $name wasn't found
 * @return mixed value of a variable, if didn't found - $default (NULL by default)
 */
    static public function getVar($name, $from = 'all', $default = NULL) {
        $from = strtolower($from);
		$getName = isset($_GET[$name]) ? self::sanitizeData($_GET[$name]) : '';
		$postName = isset($_POST[$name]) ? self::sanitizeData($_POST[$name]) : '';
        if($from == 'all') {
            if(!empty($getName)) {
                $from = 'get';
            } elseif(!empty($postName)) {
                $from = 'post';
            }
        }

        switch($from) {
            case 'get':
			$getName = isset($_GET[$name]) ? self::sanitizeData($_GET[$name]) : '';
                if(!empty($getName))
                    return $getName;
            break;
            case 'post':
			$postName = isset($_POST[$name]) ? self::sanitizeData($_POST[$name]) : '';
                if(!empty($postName))
                    return $postName;
            break;
            case 'file':
            case 'files':
                if(isset($_FILES[$name]))
                    return sanitize_file_name($_FILES[$name]);
                break;
            case 'session':
                if(isset($_SESSION[$name]))
                    return self::sanitizeData($_SESSION[$name]);
				break;
            case 'server':
                if(isset($_SERVER[$name]))
                    return $_SERVER[$name];
				break;
			case 'cookie':
				if(isset($_COOKIE[$name])) {
					$value = $_COOKIE[$name];
					if(strpos($value, '_JSON:') === 0) {
						$value = explode('_JSON:', $value);
						$value = utilsWns::jsonDecode(array_pop($value));
					}
                    return $value;
				}
				break;
        }
        return $default;
    }
    static public function sanitizeData($value) {
        if(is_array($value)) {
            $newArr = array();
            foreach($value as $k => $v)
            {
                $newArr[$k] = is_array($v) ? self::sanitizeData($v) : sanitize_text_field($v);
            }
            return $newArr;
        }
        else return sanitize_text_field($value);
    }
	static public function isEmpty($name, $from = 'all') {
		$val = self::getVar($name, $from);
		return empty($val);
	}
    static public function setVar($name, $val, $in = 'input', $params = array()) {
        $in = strtolower($in);
        switch($in) {
            case 'get':
                $_GET[$name] = sanitize_text_field($val);
            break;
            case 'post':
                $_POST[$name] = sanitize_text_field($val);
            break;
            case 'session':
                $_SESSION[$name] = sanitize_text_field($val);
            break;
			case 'cookie':
				$expire = isset($params['expire']) ? time() + $params['expire'] : 0;
				$path = isset($params['path']) ? $params['path'] : '/';
				if(is_array($val) || is_object($val)) {
					$saveVal = '_JSON:'. utilsWns::jsonEncode( $val );
				} else {
					$saveVal = $val;
				}
				setcookie($name, $saveVal, $expire, $path);
			break;
        }
    }
    static public function clearVar($name, $in = 'input', $params = array()) {
        $in = strtolower($in);
        switch($in) {
            case 'get':
                if(isset($_GET[$name]))
                    unset($_GET[$name]);
            break;
            case 'post':
                if(isset($_POST[$name]))
                    unset($_POST[$name]);
            break;
            case 'session':
                if(isset($_SESSION[$name]))
                    unset($_SESSION[$name]);
            break;
			case 'cookie':
				$path = isset($params['path']) ? $params['path'] : '/';
				setcookie($name, '', time() - 3600, $path);
			break;
        }
    }
    static public function get($what) {
        $what = strtolower($what);
        switch($what) {
            case 'get':
                return $_GET;
                break;
            case 'post':
                return $_POST;
                break;
            case 'session':
                return $_SESSION;
                break;
            case 'files':
				return $_FILES;
				break;
        }
        return NULL;
    }
    static public function getMethod() {
        if(!self::$_requestMethod) {
            self::$_requestMethod = strtoupper( self::getVar('method', 'all', $_SERVER['REQUEST_METHOD']) );
        }
        return self::$_requestMethod;
    }
    static public function getAdminPage() {
        $pagePath = self::getVar('page');
        if(!empty($pagePath) && strpos($pagePath, '/') !== false) {
            $pagePath = explode('/', $pagePath);
            return str_replace('.php', '', $pagePath[count($pagePath) - 1]);
        }
        return false;
    }
    static public function getRequestUri() {
        return $_SERVER['REQUEST_URI'];
    }
    static public function getMode() {
        $mod = '';
        if(!($mod = self::getVar('mod')))  //Frontend usage
            $mod = self::getVar('page');     //Admin usage
        return $mod;
    }
}
