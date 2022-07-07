<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src14
 *
 * @author Shinzo SAITO
 *
 * ファイル名=UserDAO.php
 * フォルダ=/ph34/sharereports/classes/daos/
 */

namespace LocalHalPH34\ShareReports\Classes\daos;

use PDO;
use LocalHalPH34\ShareReports\Classes\entities\User;

/**
 * usersテーブルへのデータ操作クラス。
 */
class UserDAO
{
    /**
     * @var PDO DB接続オブジェクト
     */
    private PDO $db;

    /**
     * コンストラクタ
     *
     * @param PDO $db DB接続オブジェクト
     */
    public function __construct(PDO $db)
    {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db = $db;
    }

    /**
     * ログインIDによる検索。
     *
     * @param string $loginId ログインID。
     * @return User 該当するUserオブジェクト。ただし、該当データがない場合はnull。41 */
    public function findByLoginid(string $loginId): ?User
    {
        $sql = "SELECT * FROM users WHERE us_mail = :login";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":login", $loginId, PDO::PARAM_INT);
        $result = $stmt->execute();
        $user = null;
        if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $login = $row["us_auth"];
            $name = $row["us_name"];
            $passwd = $row["us_password"];
            $mail = $row["us_mail"];

            $user = new User();
            $user->setId($id);
            $user->setLogin($login);
            $user->setName($name);
            $user->setPasswd($passwd);
            $user->setMail($mail);
        }
        return $user;
    }
}
