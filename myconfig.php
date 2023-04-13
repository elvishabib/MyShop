<?php

session_start();


$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'username' => 'root',
        'password' => '#Heuredegloire1@',
        'db' => 'my_shop'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

class Config
{
    public static function get($path = null)
    {
        if ($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            foreach ($path as $bit) {
                if (isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}

class DB
{

    private static $_instance = null;
    private $_pdo;
    private $_query;
    private $_error = false;
    private $_results;
    private $_count = 0;

    public function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

    public function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=', '!=', 'LIKE', 'IN');

            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    public function error()
    {
        return $this->_error;
    }

    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }


    public function getAll($action, $table)
    {
        $sql = "{$action} FROM {$table}";
        if (!$this->query($sql, array($table))->error()) {
            return $this;
        }
    }
    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields = array())
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = '';
            $x = 1;
            foreach ($fields as $field) {
                $values .= '?';
                if ($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys)  . "`) VALUES ({$values})";

            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

    public function update($table, $id, $fields)
    {
        $set = "";
        $x = 1;
        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }

    public function count()
    {
        return $this->_count;
    }

    public function results()
    {
        return $this->_results;
    }
    public function first()
    {
        return $this->results()[0];
    }
}


class User
{
    private $_db;
    private $_data;
    private $_sessionName;
    private $_isLoggedIn;
    private $_cookieName;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                }
            } elseif (Cookie::exists($this->_cookieName)) {
                $user = Cookie::get($this->_cookieName);
                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem account not created !');
        }
    }

    public function find($user = null)
    {
        if ($user) {
            $field = 'email';
            $data = $this->_db->get('users', array($field, '=', $user));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    public function login($email = null, $password = null, $remember)
    {
        $user = $this->find($email);
        if ($user) {
            if (Hash::verify($password, $this->data()->password) == true) {

                if ($remember) {
                    Cookie::put($this->_cookieName, $this->data()->email, Config::get('remember/cookie_expiry'));
                } else {
                    Session::put($this->_sessionName, $this->data()->email);
                }
                return true;
            }
        }

        return false;
    }
    public function data()
    {
        return $this->_data;
    }
    public function isLoggedIn()
    {

        return $this->_isLoggedIn;
    }
    public function logout()
    {
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }
}

class Product
{
    private $_db;
    private $_data;

    public function __construct($user = null)
    {
        $this->_db = DB::getInstance();
    }
    public function find($product = null)
    {
        if ($product) {
            $field = 'name';
            $data = $this->_db->get('products', array($field, '=', $product));

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }
        return false;
    }
    public function createProduct($fields = array())
    {
        if (!$this->_db->insert('products', $fields)) {
            throw new Exception('There was a problem product not created !');
        }
    }
    public function data()
    {
        return $this->_data;
    }
}


class Hash
{
    public static function make($string)
    {
        return password_hash($string, PASSWORD_BCRYPT);
    }

    public static function unique()
    {
        return self::make(uniqid());
    }

    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}

class Cookie
{
    public static function exists($name)
    {
        return (isset($_COOKIE[$name])) ? true : false;
    }
    public static function get($name)
    {
        return $_COOKIE[$name];
    }

    public static function put($name, $value, $expiry)
    {
        if (setcookie($name, $value, time() + $expiry, "/")) {
            return true;
        }
        return false;
    }

    public static function delete($name)
    {
        self::put($name, '', time() - 1);
    }
}

class Session
{
    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }
    public static function get($name)
    {
        return $_SESSION[$name];
    }
    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
    public static function flash($name, $string = '')
    {
        if (self::exists($name)) {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $string);
        }
    }
}

class Token
{
    public static function generate()
    {
        return Session::put(Config::get('session/token_name'), md5(uniqid()));
    }
    public static function check($token)
    {
        $tokenName = Config::get('session/token_name');
        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}

class Input
{
    public static function exists($type = "post")
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;

                break;
            default:
                return false;
                break;
        }
    }
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } elseif (isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
}

class Redirect
{
    public static function to($location = null)
    {
        if ($location) {
            header('Location: ' . $location);
        }
    }
}

class Validate
{
    private $_passed = false;
    private $_errors = array();
    private $_db = null;

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = $source[$item];
                $item = escape($item);
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                            break;
                        case 'max';
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError("{$item} already exists");
                            }
                            break;
                        case 'is_email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL) == true) {
                                $this->addError("Not a valid {$item}");
                            }
                            break;
                        case 'int':
                            if (!is_numeric($value)) {
                                $this->addError("{$item} must be numeric");
                            }
                            break;
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }

        return $this;
    }
    public function addError($error)
    {
        $this->_errors[] = $error;
    }
    public function errors()
    {
        return $this->_errors;
    }
    public function passed()
    {
        return $this->_passed;
    }
}


function escape($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}