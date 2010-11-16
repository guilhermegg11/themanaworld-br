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

class CharParser {
    /// Store character ID
    protected $id;
    /// Account ID that owns this character
    protected $account_id;
    /// Character ID for this account ID
    protected $char_num;
    /// Character class
    protected $char_class;
    /// Character name
    protected $name;

    protected $base = array('level' => NULL, 'exp' => NULL);

    protected $job = array('level' => NULL, 'exp' => NULL);

    protected $zeny;

    protected $hp = array('current' => NULL, 'max' => NULL);

    protected $sp = array('current' => NULL, 'max' => NULL);

    protected $attr = array('str' => NULL, 'agi' => NULL, 'vit' => NULL, 'int' => NULL, 'dex' => NULL, 'luk' => NULL);

    protected $status_point;

    protected $status_skill;

    protected $option;

    protected $karma;

    protected $manner;

    protected $party_id;

    protected $guild_id;

    protected $hair = array('type' => NULL, 'color' => NULL);

    protected $clothes_color;

    protected $equips = array('weapon' => NULL, 'shield' => NULL, 'head' => array('top' => NULL, 'mid' => NULL, 'bottom' => NULL));

    protected $last_point = array('x' => NULL, 'y' => NULL);

    protected $save_point = array('x' => NULL, 'y' => NULL);

    protected $partner_id;

    static public function parseLine($line)
    {
        $char = new CharParser();
        $tabfds = explode("\t", $line);
        $char->account_id = intval($tabfds[0]);
        $commasfds = explode(',', $tabfds[1]);
        $char->char_num = intval($commasfds[0]);
        $char->char_class = intval($commasfds[1]);
        $char->name = $tabfds[2];
        $commasfds = explode(',', $tabfds[3]);
        $char->char_class = intval($commasfds[0]);
        $char->base['level'] = intval($commasfds[1]);
        $char->job['level'] = intval($commasfds[2]);
        $commasfds = explode(',', $tabfds[4]);
        $char->base['exp'] = intval($commasfds[0]);
        $char->job['exp'] = intval($commasfds[1]);
        $char->zeny = intval($commasfds[2]);
        $commasfds = explode(',', $tabfds[5]);
        $char->hp['current'] = $commasfds[0];
        $char->hp['max'] = $commasfds[1];
        $char->sp['current'] = $commasfds[2];
        $char->sp['max'] = $commasfds[3];
        /* &tmp_int[13], &tmp_int[14], &tmp_int[15], &tmp_int[16], &tmp_int[17], &tmp_int[18] */
        /* &tmp_int[19], &tmp_int[20] */
        /* &tmp_int[21], &tmp_int[22], &tmp_int[23] */
        /* &tmp_int[24], &tmp_int[25], &tmp_int[26] */
        /* &tmp_int[27], &tmp_int[28], &tmp_int[29] */
        /* &tmp_int[30], &tmp_int[31], &tmp_int[32], &tmp_int[33], &tmp_int[34] */
    }
}

/// Class for parsing gm log file
class LogParser {
    /// Log lines must be returned as array
    const VALUE_RET     = 0x01;
    /// Log lines must be printed out on output
    const VALUE_PRINT   = 0x02;
    /// Enable all log output values
    const VALUE_ALL     = 0x03;

    /**
     * @brief Get whole log file
     *
     * @param string|resource $file Log file path or file descriptor resource
     * @param int $mode Set output mode
     */
    static public function parseLog($file, $mode = self::VALUE_ALL)
    {
        if ($mode | self::VALUE_ALL)
        {
            $ret = array();
            $close_it = FALSE;
            if (is_string($file))
            {
                $file = fopen($file, 'r');
                if ($file === FALSE) return FALSE;
                $close_it = TRUE;
            }
            if (is_resource($file))
            {
                while (is_string($line=fgets($file)))
                {
                    if ($mode | self::VALUE_PRINT) echo $line;
                    if ($mode | self::VALUE_RET) $ret[] = $line;
                }
                if ($close_it) fclose($file);
                if ($mode | self::VALUE_RET) return $ret;
            }
        }
    }

    /**
     * @brief Get last lines of log file
     *
     * @param string|resource $file Log file path or file descriptor resource
     * @param int $limit Number of lines to output
     * @param int $mode Set output mode
     */
    static public function parseLogLast($file, $limit, $mode = self::VALUE_ALL)
    {
        if ($mode | self::VALUE_ALL && is_numeric($limit))
        {
            $ret = array();
            $close_it = FALSE;
            if (is_string($file))
            {
                $file = fopen($file, 'r');
                if ($file === FALSE) return FALSE;
                $close_it = TRUE;
            }
            if (is_resource($file))
            {
                $psret = FALSE;
                $ps = proc_open(
                    "tail -n $limit",
                    array(
                        array('pipe', 'r'),
                        array('pipe', 'w'),
                        array('file', '/dev/null', 'w')
                    ),
                    $psfd
                );
                if (is_resource($ps))
                {
                    stream_copy_to_stream($file, $psfd[0]);
                    fclose($psfd[0]);
                    while (is_string($line=fgets($psfd[1])))
                    {
                        if ($mode | self::VALUE_PRINT) echo $line;
                        if ($mode | self::VALUE_RET) $ret[] = $line;
                    }
                    fclose($psfd[1]);
                    $psret = proc_close($ps);
                }
                if ($close_it) fclose($file);
                if ($mode | self::VALUE_RET && $psret === 0) return $ret;
            }
        }
    }

    /**
     * @brief Get filtered date lines of log file
     *
     * @param string|resource $file Log file path or file descriptor resource
     * @param array $date Array in format of getdate()
     * @param int $mode Set output mode
     */
    static public function parseLogDate($file, $date, $mode = self::VALUE_ALL)
    {
        if ($mode | self::VALUE_ALL && is_array($date))
        {
            $ret = array();
            $close_it = FALSE;
            if (is_string($file))
            {
                $file = fopen($file, 'r');
                if ($file === FALSE) return FALSE;
                $close_it = TRUE;
            }
            if (is_resource($file))
            {
                $date_re = '/^\[';
                if (isset($date['year']) && is_numeric($date['year']) && $date['year'] >= 2000 && $date['year'] <= 9999)
                    $date_re .= intval($date['year']);
                else
                    $date_re .= '[2-9][0-9]{3}';
                $date_re .= '-';
                if (isset($date['mon']) && is_numeric($date['mon']) && $date['mon'] >= 1 && $date['mon'] <= 12)
                    $date_re .= sprintf('%02d', $date['mon']);
                else
                    $date_re .= '(0?[1-9]|1[0-2])';
                $date_re .= '-';
                if (isset($date['mday']) && is_numeric($date['mday']) && $date['mday'] >= 1 && $date['mday'] <= 31)
                    $date_re .= sprintf('%02d', $date['mday']);
                else
                    $date_re .= '(0?[1-9]|[1-2][0-9]|3[0-1])';
                $date_re .= ' ';
                if (isset($date['hours']) && is_numeric($date['hours']) && $date['hours'] >= 0 && $date['hours'] <= 23)
                    $date_re .= sprintf('%02d', $date['hours']);
                else
                    $date_re .= '([0-1]?[0-9]|2[0-3])';
                $date_re .= ':';
                if (isset($date['minutes']) && is_numeric($date['minutes']) && $date['minutes'] >= 0 && $date['minutes'] <= 59)
                    $date_re .= sprintf('%02d', $date['minutes']);
                else
                    $date_re .= '[0-5]?[0-9]';
                $date_re .= ':';
                if (isset($date['seconds']) && is_numeric($date['seconds']) && $date['seconds'] >= 0 && $date['seconds'] <= 59)
                    $date_re .= sprintf('%02d', $date['seconds']);
                else
                    $date_re .= '[0-5]?[0-9]';
                $date_re .= '\] /';
                while (is_string($line=fgets($file)))
                {
                    if (preg_match($date_re, $line))
                    {
                        if ($mode | self::VALUE_PRINT) echo $line;
                        if ($mode | self::VALUE_RET) $ret[] = $line;
                    }
                }
                if ($close_it) fclose($file);
                if ($mode | self::VALUE_RET) return $ret;
            }
        }
    }
}


