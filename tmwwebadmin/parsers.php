<?php

class AccountParser {
    /// Store account ID
    protected $id;
    /// Store account name
    protected $name;
    /// Store account password
    protected $passwd;
    /// Date with last account login
    protected $last_login;
    /// Player sex
    protected $sex;
    /// Total of player connections
    protected $login_count;
    /// Account state
    protected $state;
    /// Associated e-mail
    protected $email;
    /// Error message for status 7
    protected $error_message;
    /// Account validity time
    protected $validity_time;
    /// Last login IP
    protected $last_ip;
    /// Memo field
    protected $memo;
    /// Ban time
    protected $ban_time;
    
    static public function parseLine($line)
    {
        $re = preg_match(
        	'/^(\d+)\t(.*)\t(.*)\t(.*)\t(\w)\t(\d+)\t(\d+)\t(.*)\t(.*)\t(\d+)\t(.*)\t(.*)\t(\d+)\t$/',
            $line, $match
        );
        if (!$re) return FALSE;
        $acc = new AccountParser();
        $acc->id = intval($match[1]);
        $acc->name = $match[2];
        $acc->passwd = $match[3];
        $acc->last_login = $match[4];
        $acc->sex = $match[5];
        $acc->login_count = intval($match[6]);
        $acc->state = intval($match[7]);
        $acc->email = $match[8];
        $acc->error_message = $match[9];
        $acc->validity_time = intval($match[10]);
        $acc->last_ip = $match[11];
        $acc->memo = $match[12];
        $acc->ban_time = intval($match[13]);
        return $acc;
    }
    
    static public function parseFile($file)
    {
        $close_it = FALSE;
        if (is_string($file))
        {
            $file = fopen($file, 'r');
            if ($file === FALSE) return FALSE;
            $close_it = TRUE;
        }
        if (is_resource($file))
        {
            $acclist = array();
            while (!feof($file))
            {
                $line = fgets($file);
                if ($line === FALSE) break;
                $acc = self::parseLine($line);
                if ($acc === FALSE || !is_a($acc, 'AccountParser')) continue;
                array_push($acclist, $acc);
            }
            if ($close_it) fclose($file);
            return $acclist;
        }
        return FALSE;
    }
    
    static public function forEachExec($file, $funcname, &$userdata = NULL)
    {
        $close_it = FALSE;
        if (is_string($file))
        {
            $file = fopen($file, 'r');
            if ($file === FALSE) return FALSE;
            $close_it = TRUE;
        }
        if (is_resource($file))
        {
            $count = 0;
            while (!feof($file))
            {
                $line = fgets($file);
                if ($line === FALSE) break;
                $acc = self::parseLine($line);
                if ($acc === FALSE || !is_a($acc, 'AccountParser')) continue;
                $count++;
                if (call_user_func($funcname, $acc, &$userdata) === FALSE) break;
            }
            if ($close_it) fclose($file);
            return $count;
        }
        return FALSE;
    }
    
    public function getId()
    {
        if ($this->id < 0) return FALSE;
        return $this->id;
    }
    public function getName() { return $this->name; }
    public function getPassword() { return $this->passwd; }
    public function getLastLogin()
    {
        if (!preg_match('/^\d{4}-(0\d|1[0-2])-([0-2]\d|3[0-1]) ([0-1]\d|2[0-3]):[0-5]\d:[0-5]\d\.\d{3}$/',
                        $this->last_login)) return FALSE;
        return $this->last_login;
    }
    public function getSex()
    {
        switch ($this->sex)
        {
        case 'M':
        case 'F':
        case 'S':
            return $this->sex;
        }
        return FALSE;
    }
    public function getLoginCount()
    {
        if ($this->login_count < 0) return FALSE;
        return $this->login_count;
    }
    public function getAccountState()
    {
        if ($this->state < 0 || $this->state > 10) return FALSE;
        return $this->state;
    }
    public function getEMail()
    {
        if (!preg_match('/.+@.+\..+/', $this->email)) return FALSE;
        return $this->email;
    }
    public function getErrorMessage()
    {
        if ($this->state != 7) return FALSE;
        return $this->error_message;
    }
    public function getValidity() { return $this->validity_time; }
    public function getValidityTime()
    {
        $dt = new DateTime();
        $dt->setTimestamp($this->validity_time);
        return $dt;
    }
    public function getLastIP()
    {
        if (!preg_match('/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/',
                        $this->last_ip)) return FALSE;
        return $this->last_ip;
    }
    public function getMemo() { return $this->memo; }
    public function getBan() { return $this->ban_time; }
    public function getBanTime()
    {
        $dt = new DateTime();
        $dt->setTimestamp($this->ban_time);
        return $dt;
    }
}
