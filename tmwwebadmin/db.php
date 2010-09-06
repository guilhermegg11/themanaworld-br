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

  CREATE TABLE `operation_log` (
  	`login` VARCHAR(23) NOT NULL,
  	`operation_date` DATETIME NOT NULL,
  	`operation_action` ENUM(
  		'REQ_CHMAIL',
  		'CFM_CHMAIL',
  		'REQ_CHPASS',
  		'CFM_CHPASS',
  		'REQ_REG',
  		'CFM_REG'
  	) NOT NULL,
  	`ip` VARCHAR(64) NOT NULL,
  	`extra_data` VARCHAR(255) DEFAULT NULL,
  	INDEX `idx_operation_log1` (`login` ASC),
  	INDEX `idx_operation_log2` (`operation_date` ASC)
  );

CREATE TABLE `votes` (
  `id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)
);

CREATE TABLE `vote_user` (
  `login` VARCHAR(23) NOT NULL,
  `votes_id` INT UNSIGNED NOT NULL,
  `date_event` DATETIME NOT NULL,
  INDEX `fk_table1_votes` (`votes_id` ASC),
  PRIMARY KEY (`login`, `votes_id`),
  CONSTRAINT `fk_table1_votes`
    FOREIGN KEY (`votes_id` )
    REFERENCES `votes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

CREATE TABLE `vote_choice` (
  `votes_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(20) NOT NULL,
  `description` VARCHAR(45) NULL,
  `count` INT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`votes_id`, `name`),
  INDEX `fk_vote_choice_votes1` (`votes_id` ASC),
  CONSTRAINT `fk_vote_choice_votes1`
    FOREIGN KEY (`votes_id` )
    REFERENCES `votes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

  CREATE TABLE `confirm_register` (
  	`login` VARCHAR(23) NOT NULL UNIQUE,
  	`email` VARCHAR(39) NOT NULL UNIQUE,
  	`request_date` DATETIME NOT NULL,
  	`verification_code` CHAR(64) NOT NULL,
  	`error_count` INT NOT NULL DEFAULT 0
  );
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
        $this->prepStmt['HAS_EMAIL1'] =& $this->prepare('SELECT COUNT(`login`)
        FROM `confirm_email`
        WHERE `email` = ?');
        $this->prepStmt['HAS_EMAIL2'] =& $this->prepare('SELECT COUNT(`login`)
        FROM `confirm_register`
        WHERE `email` = ?');
        $this->prepStmt['SET_OPER_LOG'] =& $this->prepare('INSERT INTO `operation_log`
        (`login`, `operation_date`, `operation_action`, `ip`)
        	VALUES
        (?, NOW(), ?, ?)');
        $this->prepStmt['SET_OPER_LOG_DATA'] =& $this->prepare('INSERT INTO `operation_log`
        (`login`, `operation_date`, `operation_action`, `ip`, `extra_data`)
        	VALUES
        (?, NOW(), ?, ?, ?)');
        $this->prepStmt['ADD_VOTE1'] =& $this->prepare('INSERT INTO `vote_user`
        (`login`, `votes_id`, `date_event`)
            VALUES
        (?, ?, NOW())');
        $this->prepStmt['ADD_VOTE2'] =& $this->prepare('UPDATE `vote_choice`
        SET `count`=`count`+1
        WHERE `votes_id`=? AND `name`=?');
        $this->prepStmt['EXIST_VOTE'] =& $this->prepare('SELECT COUNT(`login`)
        FROM `vote_user`
        WHERE `votes_id` = ? AND `login` = ?');
        $this->prepStmt['SET_REG_CODE'] =& $this->prepare('INSERT INTO `confirm_register`
        (`login`, `email`, `request_date`, `verification_code`)
            VALUES
        (?, ?, NOW(), ?)');
        $this->prepStmt['DEL_REG_CODE'] =& $this->prepare('DELETE FROM `confirm_register`
        WHERE `login` = ?');
        $this->prepStmt['INC_REG_CODE'] =& $this->prepare('UPDATE `confirm_email`
        SET `error_count`=`error_count`+1
        WHERE `login` = ?');
        $this->prepStmt['GET_REG_CODE'] =& $this->prepare(
                        'SELECT `verification_code`, `error_count`, `email`
        FROM `confirm_register`
        WHERE `login` = ?');
    }

    private function tmw_start_transaction()
    {
        if (!$this->autocommit(FALSE)) return FALSE;
        return TRUE;
    }

    private function tmw_rollback()
    {
        $this->rollback();
        $this->autocommit(TRUE);
        return FALSE;
    }

    private function tmw_commit()
    {
        if (!$this->commit()) return $this->tmw_rollback();
        $this->autocommit(TRUE);
        return TRUE;
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
        if (!$stmt->fetch())
        {
            $stmt->free_result();
            return 0;
        }
        $stmt->free_result();
        if ($error > 30)
        {
            if ($this->delete_email_code($login) === FALSE) return FALSE;
            return -1;
        }
        return $code;
    }
    
    public function has_email($email)
    {
        for ($i = 1; $i < 3; $i++)
        {
            $stmt =& $this->prepStmt["HAS_EMAIL$i"];
            $stmt->bind_param('s', $email);
            if (!$stmt->execute()) return FALSE;
            $stmt->bind_result($count);
            if (!$stmt->fetch()) return FALSE;
            $stmt->free_result();
            if (!empty($count)) break;
        }
        return $count;
    }
    
    public function set_operation_log($login, $action, $data = FALSE)
    {
        if ($data === FALSE)
        {
            $stmt =& $this->prepStmt['SET_OPER_LOG'];
            $stmt->bind_param('sss', $login, $action, $_SERVER['REMOTE_ADDR']);
            if (!$stmt->execute()) return FALSE;
        }
        else
        {
            $stmt =& $this->prepStmt['SET_OPER_LOG_DATA'];
            $stmt->bind_param('ssss', $login, $action, $_SERVER['REMOTE_ADDR'], $data);
            if (!$stmt->execute()) return FALSE;
        }
        return TRUE;
    }

    /* UNSUPPORTED TRANSACTION IN MYISAM
    public function add_vote($login, $id, $name)
    {
        if (!$this->tmw_start_transaction()) return FALSE;
        $stmt1 =& $this->prepStmt['ADD_VOTE1'];
        $stmt2 =& $this->prepStmt['ADD_VOTE2'];
        $stmt1->bind_param('si', $login, $id);
        if (!$stmt1->execute()) return $this->tmw_rollback();
        if ($stmt1->affected_rows != 1) return $this->tmw_rollback();
        $stmt2->bind_param('is', $id, $name);
        if (!$stmt2->execute()) return $this->tmw_rollback();
        if ($stmt2->affected_rows != 1) return $this->tmw_rollback();
        return $this->tmw_commit();
    } */

    public function add_vote($login, $id, $name)
    {
        $stmt1 =& $this->prepStmt['ADD_VOTE1'];
        $stmt2 =& $this->prepStmt['ADD_VOTE2'];
        $stmt1->bind_param('si', $login, $id);
        if (!$stmt1->execute()) return FALSE;
        if ($stmt1->affected_rows != 1) return FALSE;
        $stmt2->bind_param('is', $id, $name);
        if (!$stmt2->execute()) return FALSE;
        if ($stmt2->affected_rows != 1) return FALSE;
        return TRUE;
    }

    public function has_vote($login, $id)
    {
        $stmt =& $this->prepStmt['EXIST_VOTE'];
        $stmt->bind_param('is', $id, $login);
        if (!$stmt->execute()) return FALSE;
        $stmt->bind_result($count);
        if (!$stmt->fetch()) return FALSE;
        $stmt->free_result();
        return $count;
    }

    public function set_register_code($login, $email)
    {
        $stmt =& $this->prepStmt['SET_REG_CODE'];
        $code = $this->get_random();
        $stmt->bind_param('sss', $login, $email, $code);
        if (!$stmt->execute()) return FALSE;
        if (!$stmt->affected_rows) return 0;
        return $code;
    }

    public function delete_register_code($login)
    {
        $stmt =& $this->prepStmt['DEL_REG_CODE'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;        
    }

    public function incr_register_errcount($login)
    {
        $stmt =& $this->prepStmt['INC_REG_CODE'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        return $stmt->affected_rows;
    }

    public function get_register_code($login, &$email = FALSE)
    {
        $stmt =& $this->prepStmt['GET_REG_CODE'];
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) return FALSE;
        $stmt->bind_result($code, $error, $email);
        if (!$stmt->fetch())
        {
            $stmt->free_result();
            return 0;
        }
        $stmt->free_result();
        if ($error > 30)
        {
            if ($this->delete_email_code($login) === FALSE) return FALSE;
            return -1;
        }
        return $code;
    }
};

?>