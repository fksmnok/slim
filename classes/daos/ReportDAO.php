<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src13
 *
 * @author Shinzo SAITO
 *
 * ファイル名=DeptDAO.php
 * フォルダ=/ph34/sharereports/classes/daos/
 */

namespace LocalHalPH34\ShareReports\Classes\daos;

use PDO;
use LocalHalPH34\ShareReports\Classes\entities\Report;

/**
 * deptテーブルへのデータ操作クラス。
 */
class ReportDAO
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
     * 主キーidによる検索。
     *
     * @param integer $id 主キーであるid。
     * @return Dept 該当するDeptオブジェクト。ただし、該当データがない場合はnull。
     */
    public function findByPK(int $id): ?Report
    {
        $sql = "SELECT * FROM reports WHERE id = :id" ;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        $share = null;
        if ($result && $row = $stmt->fetch()) {
            $idDb = $row["id"];
            $rpDate = $row["rp_date"];
            $rpFrom = $row["rp_time_from"];
            $rpTo = $row["rp_time_to"];
            $rpCn = $row["rp_content"];
            $rpAt = $row["rp_created_at"];
            $rpId = $row["reportcate_id"];
            $usId = $row["user_id"];

            $share = new Report();
            $share->setId($idDb);
            $share->setRpDate($rpDate);
            $share->setRpFrom($rpFrom);
            $share->setRpTo($rpTo);
            $share->setRpContent($rpCn);
            $share->setRpAt($rpAt);
            $share->setRpId($rpId);
            $share->setUsId($usId);
        }
        return $share;
    }

    public function findByUsName(int $usId): ?Report
    {
        $sql = "SELECT * FROM users WHERE us_auth = :us_auth" ;
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":us_auth", $usId, PDO::PARAM_INT);
        $result = $stmt->execute();
        $share = null;
        if ($result && $row = $stmt->fetch()) {
            $usName = $row["us_name"];
            $usMail = $row["us_mail"];
            $share = new Report();
            $share->setUsName($usName);
            $share->setUsMail($usMail);
        }
        return $share;
    }

    /**
     * 部門番号による検索。
     *
     * @param integer $dpNo 主キーであるid。
     * @return Dept 該当するDeptオブジェクト。ただし、該当データがない場合はnull。68 */
    // public function findByDpNo(int $dpNo): ?Dept
    // {
    //     $sql = "SELECT * FROM depts WHERE dp_no = :dp_no";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindValue(":dp_no", $dpNo, PDO::PARAM_INT);
    //     $result = $stmt->execute();
    //     $dept = null;
    //     if ($result && $row = $stmt->fetch()) {
    //         $id = $row["id"];
    //         $dpNoDB = $row["dp_no"];
    //         $dpName = $row["dp_name"];
    //         $dpLoc = $row["dp_loc"];

    //         $dept = new Dept();
    //         $dept->setId($id);
    //         $dept->setDpNo($dpNoDB);
    //         $dept->setDpName($dpName);
    //         $dept->setDpLoc($dpLoc);
    //     }
    //     return $dept;
    // }

    /**
     * 全部門情報検索。
     *
     * @return array  全部門情報が格納された連想配列。キーは部門番号、値はDeptエンティティオブジェクト。
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM reports  ORDER BY rp_date DESC";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();
        $rpList = [];
        while ($row = $stmt->fetch()) {
            $id = $row["id"];
            $rpDate = $row["rp_date"];
            $rpFrom = $row["rp_time_from"];
            $rpTo = $row["rp_time_to"];
            $rpCn = $row["rp_content"];
            $rpAt = $row["rp_created_at"];
            $rpId = $row["reportcate_id"];
            $usId = $row["user_id"];

            $share = new Report();
            $share->setId($id);
            $share->setRpDate($rpDate);
            $share->setRpFrom($rpFrom);
            $share->setRpTo($rpTo);
            $share->setRpContent($rpCn);
            $share->setRpAt($rpAt);
            $share->setRpId($rpId);
            $share->setUsId($usId);
            $rpList[$id] = $share;
        }
        return $rpList;
    }

    public function findCate(): array
    {
        $sql = "SELECT * FROM reportcates ORDER BY rc_order";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();
        $rcList = [];
        while ($row = $stmt->fetch()) {
            $rcid = $row["id"];
            $rcName = $row["rc_name"];
    
            $share = new Report();
            $share->setRcId($rcid);
            $share->setRcName($rcName);
            $rcList[$rcid] = $share;
        }
        return $rcList;
    }

    public function findCateName(int $rpId): ?Report
    {
        $sql = "SELECT * FROM reportcates WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $rpId, PDO::PARAM_INT);
        $result = $stmt->execute();
        $rcList = [];
        while ($row = $stmt->fetch()) {
            $rcid = $row["id"];
            $rcName = $row["rc_name"];
    
            $share = new Report();
            $share->setRcId($rcid);
            $share->setRcName($rcName);
        }
        return $share;
    }

    /**
     * 部門情報登録。
     *
     * @param Dept $dept 登録情報が格納されたDeptオブジェクト。
     * @return integer 登録情報の連番主キーの値。登録に失敗した場合は-1。
     */
    public function insert(Report $share): int
    {
        $sqlInsert = "INSERT INTO reports (rp_date, rp_time_from, rp_time_to, rp_content, rp_created_at, reportcate_id, user_id) VALUES (:rp_date, :rp_time_from, :rp_time_to, :rp_content, :rp_created_at, :reportcate_id, :user_id)";
        $stmt = $this->db->prepare($sqlInsert);
        $stmt->bindValue(":rp_date", $share->getRpDate(), PDO::PARAM_STR);
        $stmt->bindValue(":rp_time_from", $share->getRpFrom(), PDO::PARAM_STR);
        $stmt->bindValue(":rp_time_to", $share->getRpTo(), PDO::PARAM_STR);
        $stmt->bindValue(":rp_content", $share->getRpContent(), PDO::PARAM_STR);
        $stmt->bindValue(":rp_created_at", $share->getRpAt(), PDO::PARAM_STR);
        $stmt->bindValue(":reportcate_id", $share->getRpId(), PDO::PARAM_INT);
        $stmt->bindValue(":user_id", $share->getUsId(), PDO::PARAM_INT);
        $result = $stmt->execute();
        if ($result) {
            $id = $this->db->lastInsertId();
        } else {
            $id = -1;
        }
        return $id;
    }

    /**
     * 部門情報更新。更新対象は1レコードのみ。
     *
     * @param Dept $dept  
     * 新情報が格納されたDeptオブジェクト。主キーがこのオブジェクトのidの値のレコードを更新する。
     * @return boolean 登録が成功したかどうかを表す値。
     */
    public function update(Report $share): bool
    {
        $sqlUpdate = "UPDATE reports SET rp_date = :rp_date, rp_time_from = :rp_time_from, rp_time_to = :rp_time_to, rp_content = :rp_content, reportcate_id = :reportcate_id WHERE id = :id";
        $stmt = $this->db->prepare($sqlUpdate);
        $stmt->bindValue(":id", $share->getId(), PDO::PARAM_INT);
        $stmt->bindValue(":rp_date", $share->getRpDate(), PDO::PARAM_INT);
        $stmt->bindValue(":rp_time_from", $share->getRpFrom(), PDO::PARAM_STR);
        $stmt->bindValue(":rp_time_to", $share->getRpTo(), PDO::PARAM_STR);
        $stmt->bindValue(":rp_content", $share->getRpContent(), PDO::PARAM_STR);
        //$stmt->bindValue(":rp_created_at", $share->getRpAt(), PDO::PARAM_STR);
        $stmt->bindValue(":reportcate_id", $share->getRpId(), PDO::PARAM_INT);
        //$stmt->bindValue(":user_id", $share->getUsId(), PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result;
    }

    /**
     * 部門情報削除。削除対象は1レコードのみ。
     *
     * @param integer $id 削除対象の主キー。
     * @return boolean 登録が成功したかどうかを表す値。
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM reports WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $result = $stmt->execute();
        return $result;
    }
}
