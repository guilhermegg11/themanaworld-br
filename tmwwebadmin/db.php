<?php

/*

On MySQL:
  CREATE TABLE `confirm_email` (
  	`login` VARCHAR(23) NOT NULL PRIMARY KEY,
  	`email` VARCHAR(39) NOT NULL UNIQUE,
  	`request_date` DATETIME NOT NULL,
  	`verification_code` CHAR(64) NOT NULL,
  	`error_count` INT NOT NULL DEFAULT 0
  );

  CREATE TABLE `reset_password` (
  	`login` VARCHAR(23) NOT NULL PRIMARY KEY,
  	`request_date` DATETIME NOT NULL,
  	`request_count` INT NOT NULL DEFAULT 1,
  	`verification_code` CHAR(64) NOT NULL,
  	`error_count` INT NOT NULL DEFAULT 0
  );

 */

class TMW_MySQL extends mysqli {
    private $prepStmt = array();
    
    public function __construct($host, $port, $user, $pass, $db)
    {
        parent::__construct($host . (!$port || $port == 3306 ? '' : ":$port"), $user, $pass, $db);
        $this->prepStmt['SET_RECOVER_PASS'] =& $this->prepare('INSERT INTO `reset_password`
        (`login`, `request_date`, `verification_code`)
        	VALUES
        (?, NOW(), ?)');
        $this->prepStmt['DEL_RECOVER_PASS'] =& $this->prepare('DELETE FROM `reset_password`
        WHERE `login` = ?');
        $this->prepStmt['INC_RECOVER_PASS_REQCNT'] =& $this->prepare('UPDATE `reset_password`
        SET `request_count`=`request_count`+1, `error_count`=0
        WHERE `login` = ?');
        $this->prepStmt['INC_RECOVER_PASS_ERRCNT'] =& $this->prepare('UPDATE `reset_password`
        SET `error_count`=`error_count`+1
        WHERE `login` = ?');
        $this->prepStmt['GET_RECOVER_PASS'] =& $this->prepare(
        		'SELECT `verification_code`, `request_count`, `error_count`
        FROM `reset_password`
        WHERE `login` = ?');
        $this->prepStmt['SET_EMAIL_CODE'] =& $this->prepare('INSERT INTO `confirm_email`
        (`login`, `email`, `request_date`, `verification_code`)
        	VALUES
        (?, ?, NOW(), ?)');
        $this->prepStmt['DEL_EMAIL_CODE'] =& $this->prepare('DELETE FROM `confirm_email`
        WHERE `login` = ?');
        $this->prepStmt['INC_EMAIL_CODE'] =& $this->prepare('UPDATE `confirm_email`
        SET `error_count`=`error_count`+1
        WHERE `login` = ?');
        $this->prepStmt['GET_EMAIL_CODE'] =& $this->prepare(
        		'SELECT `verification_code`, `error_count`, `email`
        FROM `confirm_email`
        WHERE `login` = ?');
        $this->prepStmt['HAS_EMAIL'] =& $this->prepare('SELECT COUNT(`login`)
        FROM `confirm_email`
        WHERE `email` = ?');
    }

    private function get_random()
    {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $maxrand = strlen($chars) - 1;
        $code = '';
        for ($i = 0; $i < 64; $i++)
            $code .= $chars[rand(0, $maxrand)];
        return $code;
    }

    public function set_recover_password_code($login)
    {
        $stmt =& $this->prepStmt['SET_RECOVER_PASS'];
        $code = $this->get_random();
        $stmt->bind_param('ss', $login, $code);
        if (!$stmt->execute()) return FALSE;
        if (!$stmt->affected_rows) return 0;
        return $code;
    }

    public function delete_recover_password_code($login)
    {
        $stmt =& $this->prepStmt['DEL_RECOVER_PASS'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;
    }

    public function incr_recover_password_reqcount($login)
    {
        $stmt =& $this->prepStmt['INC_RECOVER_PASS_REQCNT'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;
    }

    public function incr_recover_password_errcount($login)
    {
        $stmt =& $this->prepStmt['INC_RECOVER_PASS_ERRCNT'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;
    }

    public function get_recover_password_code($login)
    {
        $stmt =& $this->prepStmt['GET_RECOVER_PASS'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        $stmt->bind_result($code, $count, $error);
        if (!$stmt->fetch())
        {
            $stmt->free_result();
            return 0;
        }
        $stmt->free_result();
        if ($count > 3 || $error > 10)
        {
            if ($this->delete_recover_password_code($login) === FALSE) return FALSE;
            return -1;
        }
        return $code;
    }
    
    public function set_email_code($login, $email)
    {
        $stmt =& $this->prepStmt['SET_EMAIL_CODE'];
        $code = $this->get_random();
        $stmt->bind_param('sss', $login, $email, $code);
        if (!$stmt->execute()) return FALSE;
        if (!$stmt->affected_rows) return 0;
        return $code;
    }
    
    public function delete_email_code($login)
    {
        $stmt =& $this->prepStmt['DEL_EMAIL_CODE'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;
    }

    public function incr_email_code_errcount($login)
    {
        $stmt =& $this->prepStmt['INC_EMAIL_CODE'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;
    }

    public function get_email_code($login, &$email = FALSE)
    {
        $stmt =& $this->prepStmt['GET_EMAIL_CODE'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        $stmt->bind_result($code, $error, $email);
        if ($error > 30)
        {
            if ($this->delete_email_code($login) === FALSE) return FALSE;
            return -1;
        }
        if (!$stmt->fetch())
        {
            $stmt->free_result();
            return 0;
        }
        $stmt->free_result();
        return $code;
    }
    
    public function has_email($email)
    {
        $stmt =& $this->prepStmt['HAS_EMAIL'];
        $stmt->bind_param('s', $email);
        if (!$stmt->execute()) return FALSE;
        $stmt->bind_result($count);
        if (!$stmt->fetch()) return FALSE;
        $stmt->free_result();
        return $count;
    }
};

?>