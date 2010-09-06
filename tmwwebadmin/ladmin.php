<?php

/**
 * This class represents a ladmin connection to eAthena Login-Server
 * */
class eAthenaLAdmin {
    /// Store socket file descriptor
    private $connfd = FALSE;
    /// Flag to identify if session is autheticated
    private $is_auth = FALSE;
    
    /// Set password encryptation type
    static public $encpass = 2;
    
    /**
     * Class constructor
     * 
     * You may initialize all connection settings in class constructor
     * 
     * @param string $ip IP address or hostname to connect
     * @param int $port Port of Login-Server
     * @param string $pass ladmin password
     * */
    function __construct($ip = FALSE, $port = FALSE, $pass = FALSE)
    {
        if ($ip)
        {
            if ($port)
                $this->connect($ip, $port, $pass);
            else
                $this->connect($ip);
        }
    }
    
    /// Class destructor
    function __destruct()
    {
        $this->disconnect();
    }
    
    /**
     * Connect this instance to a Login-Server
     * 
     * @param string $ip IP address or hostname to connect
     * @param int $port Port of Login-Server
     * @param string $pass ladmin password
     * 
     * @return boolean TRUE on success or FALSE on fail
     * */
    public function connect($ip, $port = 6901, $pass = FALSE)
    {
        if ($this->connfd !== FALSE)
            throw new Exception("You are already connected");
        $this->connfd = socket_create(AF_INET, SOCK_STREAM, 0);
        if ($this->connfd === FALSE)
            return FALSE;
        if (!socket_connect($this->connfd, $ip, $port))
        {
            $this->disconnect();
            return FALSE;
        }
        else if (is_string($pass) && strlen($pass))
            $this->authenticate($pass);
        return TRUE;
    }

    /**
     * Disconnect socket connection of ladmin
     * */
    public function disconnect()
    {
        if ($this->connfd !== FALSE)
        {
            socket_close($this->connfd);
            $this->connfd = $this->is_auth = FALSE;
        }
    }

    /**
     * Check if ladmin socket is connected
     * 
     * @return boolean TRUE if connected, FALSE if not
     * */
    public function is_connected()
    {
        if ($this->connfd === FALSE) return FALSE;
        return TRUE;
    }

    /**
     * Autheticate on Login-Server as ladmin
     * 
     * @param string $pass Password of ladmin user
     * 
     * @return boolean If connection authenticate correctly return TRUE
     * */
    public function authenticate($pass)
    {
        $pass = (string)$pass;

        if (self::$encpass == 0)
        {
            if (!socket_write($this->connfd, pack('v2a24', 0x7918, 0, $pass)))
                return FALSE;
        }
        else
        {
            if (!socket_write($this->connfd, pack('v', 0x791a)))
                return FALSE;
            if (($buf=socket_read($this->connfd, 4)) === FALSE)
                return FALSE;
            $buf = unpack('vreply/vlen', $buf);
            if ($buf['reply'] != 0x01dc || $buf['len'] < 1)
                return FALSE; // Failure on MD5 key creation
            if (($key=socket_read($this->connfd, $buf['len'])) === FALSE)
                return FALSE;
            $md5bin = md5((self::$encpass == 1 ? $key.$pass : $pass.$key), TRUE);
            if (!socket_write($this->connfd, pack('v2a16', 0x7918, self::$encpass, $md5bin)))
                return FALSE;
        }
        if (($buf=socket_read($this->connfd, 3)) === FALSE) return FALSE;
        $buf = unpack('va/cb', $buf);
        if ($buf['a'] != 0x7919 || $buf['b'] != 0)
            return FALSE; // Unauthorized IP or incorrect password
        $this->is_auth = TRUE;
        return TRUE;
    }
    
    /**
     * Verify if ladmin session was authenticated
     * 
     * @return boolean TRUE if it's authenticated or FALSE if not
     * */
    public function is_authenticated()
    {
        return $this->is_auth;
    }
    
    /**
     * Test account name validity
     * 
     * @return boolean TRUE if correct, FALSE if not
     * */
    static public function verify_account_name($name)
    {
        $name = (string)$name;
        if (preg_match('/[\x00-\x1f]/', $name))
            return FALSE;
        if (strlen($name) < 4)
            return FALSE;
        if (strlen($name) > 23)
            return FALSE;
        return TRUE;
    }
    
    /**
     * Test an e-mail validity
     * 
     * @return boolean TRUE if correct, FALSE if not
     * */
    static public function verify_email($email)
    {
        $email = (string)$email;
        if (!strlen($email) || strlen($email) > 39)
            return FALSE;
        if (!preg_match('/\@/', $email))
            return FALSE;
        if (preg_match('/[\,|\s|\;]/', $email))
            return FALSE;
        if (preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)|(\.$)/', $email) ||
            (!preg_match('/^.+\@localhost$/', $email) &&
             !preg_match('/^.+\@\[?(\w|[-.])+\.[a-zA-Z]{2,3}|[0-9]{1,3}\]?$/', $email)))
            return FALSE;
        return TRUE;
    }
    
    /**
     * Test password validity
     * 
     * @return boolean TRUE if correct, FALSE if not
     * */
    static public function verify_password($pass)
    {
        $pass = (string)$pass;
        if (preg_match('/[\x00-\x1f]/', $pass))
            return FALSE;
        if (strlen($pass) < 4)
            return FALSE;
        if (strlen($pass) > 23)
            return FALSE;
        return TRUE;
    }
    
    /**
     * This function converts a status code into a status string
     * 
     * @param int $status Status code
     * @param string $errmsg Error message for status code 7
     * 
     * @return boolean|string Status string or FALSE if status code is unknown
     * */
    static public function get_status_string($status, $errmsg = '')
    {
        switch ($status)
        {
        case 0:     return 'Account OK';
        case 1:     return 'Unregistered ID';
        case 2:     return 'Incorrect Password';
        case 3:     return 'This ID is expired';
        case 4:     return 'Rejected from Server';
        case 5:     return 'You have been blocked by the GM Team';
        case 6:     return "Your Game's EXE file is not the latest version";
        case 7:     return "You are Prohibited to log in until $errmsg";
        case 8:     return 'Server is jammed due to over populated';
        case 9:     return 'No MSG';
        case 10:    return 'This ID is totally erased';
        }
        return FALSE;
    }
    
    /**
     * Check account password
     * 
     * @param string $name Account name
     * @param string $pass Account password
     * 
     * @return mixed FALSE if account name and password doesn't match, NULL if 
     *               a connection problem happened or TRUE if match
     * */
    function check_account($name, $pass)
    {
        if (!$this->is_auth) throw new Exception('You are not autheticated');
        $name = (string)$name;
        if (!strlen($name)) throw new Exception('Invalid zero-length account name');
        if (!self::verify_account_name($name)) throw new Exception('Invalid account name');
        $pass = (string)$pass;
        if (!self::verify_account_name($pass)) throw new Exception('Invalid account password');
        if (!socket_write($this->connfd, pack('va24a24', 0x793a, $name, $pass)))
        {
            $this->disconnect();
            return NULL;
        }
        if (($buf=socket_read($this->connfd, 2)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $buf = unpack('vreply', $buf);
        if ($buf['reply'] != 0x793b)
            throw new Exception('Incorrect answer from server');
        if (($buf=socket_read($this->connfd, 28)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $account = unpack('Vid/a24name', $buf);
        if ($account['id'] == -1 || $account['id'] == 4294967295)
            return FALSE;
        return TRUE;
    }
    
    /**
     * Retrieve account info as associative array
     * 
     * Array keys are:
     * <ul><li>'id' => Account ID</li>
     * 	   <li>'gm level' => GM level of account</li>
     *     <li>'name' => Account Name</li>
     *     <li>'sex' => 'F' for female, 'M' for male, 'S' for server</li>
     * 	   <li>'count' => Connections counter</li>
     *     <li>'status' => Account status code</li>
     *     <li>'error message' => Error message for status code 7</li>
     * 	   <li>'last login' => Array with last login date</li>
     *     <li>'last ip' => IP Address used for last login</li>
     *     <li>'email' => Account e-mail</li>
     *     <li>'validate' => Array with account expire date</li>
     *     <li>'ban date' => Array with banishment date end</li>
     *     <li>'memo' => Memo for this account</li></ul>
     * 
     * @param int $id Account ID
     * 
     * @return mixed FALSE if account wasn't found, NULL if a connection 
     *               problem happened or on sucess return Array with account
     *               info
     * */
    public function info_account($id)
    {
        if (!$this->is_auth) throw new Exception('You are not autheticated');
        if (!is_numeric($id)) throw new Exception('Account ID must be a numeric value');
        $id = intval($id);
        if ($id < 0) throw new Exception('Negative account ID is not allowed');
        
        if (!socket_write($this->connfd, pack('vV', 0x7954, $id)))
        {
            $this->disconnect();
            return NULL;
        }
        if (($buf=socket_read($this->connfd, 2)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $buf = unpack('vreply', $buf);
        if ($buf['reply'] != 0x7953)
            throw new Exception('Incorrect answer from server');
        if (($buf=socket_read($this->connfd, 148)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $account = unpack(
        	'Vid/Cgm level/a24name/csex/Vcount/Vstatus/' .
        	'a20error message/a24last login/a16last ip/a40email/Vvalidate/Vban date/vmemo size',
            $buf
        );
        $account['memo'] = '';
        if ($account['memo size'] > 0)
        {
            if (($account['memo']=socket_read($this->connfd, $account['memo size'])) === FALSE ||
                !strlen($account['memo']))
            {
                $this->disconnect();
                return NULL;
            }
            unset($account['memo size']);
        }
        $account['name'] = rtrim($account['name'], "\0");
        $account['error message'] = rtrim($account['error message'], "\0");
        $account['last login'] = rtrim($account['last login'], "\0");
        $account['last ip'] = rtrim($account['last ip'], "\0");
        $account['email'] = rtrim($account['email'], "\0");
        $account['memo'] = rtrim($account['memo'], "\0");
        if (!strlen($account['name']) || $account['name'] == '')
            return FALSE;
        switch ($account['sex'])
        {
        case 0:     $account['sex']     = 'F';  break;
        case 1:     $account['sex']     = 'M';  break;
        case 2:     $account['sex']     = 'S';  break;
        default:    unset($account['sex']);
        }
        $account['last login'] = strptime($account['last login'], '%Y-%m-%d %H:%M:%S');
        $account['validate'] = ($account['validate'] ?
                                strptime($account['validate'], '%s') : FALSE);
        $account['ban date'] = ($account['ban date'] ?
                                strptime($account['ban date'], '%s') : FALSE);
        return $account;
    }
    
    /**
     * Request to change account e-mail
     * 
     * @param string $name Account name
     * @param string $email New account e-mail
     * 
     * @return mixed FALSE if account wasn't found, NULL if a connection 
     *               problem happened or TRUE on success
     * */
    public function change_email($name, $email)
    {
        if (!$this->is_auth) throw new Exception('You are not autheticated');
        $name = (string)$name;
        if (!strlen($name)) throw new Exception('Invalid zero-length account name');
        if (!self::verify_account_name($name)) throw new Exception('Invalid account name');
        $email = preg_replace('/\.\@/', '@', (string)$email);
        if (strlen($email) < 3) throw new Exception('E-Mail address to short');
        if (strlen($email) > 39) throw new Exception('E-Mail address to long');
        if (!self::verify_email($email)) throw new Exception('Invalid e-mail address');
        if (!socket_write($this->connfd, pack('va24a40', 0x7940, $name, $email)))
        {
            $this->disconnect();
            return NULL;
        }
        if (($buf=socket_read($this->connfd, 2)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $buf = unpack('vreply', $buf);
        if ($buf['reply'] != 0x7941)
            throw new Exception('Incorrect answer from server');
        if (($buf=socket_read($this->connfd, 28)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $account = unpack('Vid/a24name', $buf);
        if ($account['id'] == -1 || $account['id'] == 4294967295)
            return FALSE;
        return TRUE;
    }

    /**
     * Change some user password
     * 
     * @param string $name Account name
     * @param string $pass New account password
     * 
     * @return mixed FALSE if account wasn't found, NULL if a connection 
     *               problem happened or TRUE on success
     * */
    public function change_passwd($name, $pass)
    {
        if (!$this->is_auth) throw new Exception('You are not autheticated');
        $name = (string)$name;
        if (!strlen($name)) throw new Exception('Invalid zero-length account name');
        if (!self::verify_account_name($name)) throw new Exception('Invalid account name');
        $pass = (string)$pass;
        if (!self::verify_account_name($pass)) throw new Exception('Invalid account password');
        if (!socket_write($this->connfd, pack('va24a24', 0x7934, $name, $pass)))
        {
            $this->disconnect();
            return NULL;
        }
        if (($buf=socket_read($this->connfd, 2)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $buf = unpack('vreply', $buf);
        if ($buf['reply'] != 0x7935)
            throw new Exception('Incorrect answer from server');
        if (($buf=socket_read($this->connfd, 28)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $account = unpack('Vid/a24name', $buf);
        if ($account['id'] == -1 || $account['id'] == 4294967295)
            return FALSE;
        return TRUE;
    }

    /**
     * Request account ID from account name
     * 
     * @param string $name Account name
     * 
     * @return mixed FALSE if account wasn't found, NULL if a connection 
     *               problem happened or account ID on success
     * */
    public function id_account($name)
    {
        if (!$this->is_auth) throw new Exception('You are not autheticated');
        $name = (string)$name;
        if (!strlen($name)) throw new Exception('Invalid zero-length account name');
        if (!self::verify_account_name($name)) throw new Exception('Invalid account name');
        if (!socket_write($this->connfd, pack('va24', 0x7944, $name)))
        {
            $this->disconnect();
            return NULL;
        }
        if (($buf=socket_read($this->connfd, 2)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $buf = unpack('vreply', $buf);
        if ($buf['reply'] != 0x7945)
            throw new Exception('Incorrect answer from server');
        if (($buf=socket_read($this->connfd, 28)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $account = unpack('Vid/a24name', $buf);
        $account['name'] = rtrim($account['name'], "\0");
        if ($account['id'] == -1 || $account['id'] == 4294967295)
            return FALSE;
        return $account['id'];
    }

    /**
     * Create new account on server
     *
     * @param string $name Account name
     * @param string $sex M for male character or F for female character
     * @param string $email E-Mail associated to this account
     * @param string $pass Account password
     *
     * @return mixed FALSE if account already exists, NULL if a connection 
     *               problem happened or account ID on success
     */
    public function create_account($name, $sex, $email, $pass)
    {
        if (!$this->is_auth) throw new Exception('You are not autheticated');
        $name = (string)$name;
        if (!strlen($name)) throw new Exception('Invalid zero-length account name');
        if (!self::verify_account_name($name)) throw new Exception('Invalid account name');
        if (!preg_match('/^[MF]$/', $sex)) throw new Exception('Sex must be M or F');
        $email = preg_replace('/\.\@/', '@', (string)$email);
        if (strlen($email) < 3) throw new Exception('E-Mail address to short');
        if (strlen($email) > 39) throw new Exception('E-Mail address to long');
        if (!self::verify_email($email)) throw new Exception('Invalid e-mail address');
        $pass = (string)$pass;
        if (!self::verify_account_name($pass)) throw new Exception('Invalid account password');
        if (!socket_write($this->connfd, pack('va24a24a1a40', 0x7930, $name, $pass, $sex, $email)))
        {
            $this->disconnect();
            return NULL;
        }
        if (($buf=socket_read($this->connfd, 2)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $buf = unpack('vreply', $buf);
        if ($buf['reply'] != 0x7931)
            throw new Exception('Incorrect answer from server');
        if (($buf=socket_read($this->connfd, 28)) === FALSE || !strlen($buf))
        {
            $this->disconnect();
            return NULL;
        }
        $account = unpack('Vid/a24name', $buf);
        if ($account['id'] == -1 || $account['id'] == 4294967295)
            return FALSE;
        return $account['id'];
    }
}

?>