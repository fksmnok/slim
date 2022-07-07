<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src18
 *
 * @author Shinzo SAITO
 *
 * ファイル名=DeptController.php
 * フォルダ=/ph34/sharereports/classes/controllers/
 */

namespace LocalHalPH34\ShareReports\Classes\controllers;

use PDO;
use PDOException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LocalHalPH34\ShareReports\Classes\Conf;
use LocalHalPH34\ShareReports\Classes\exceptions\DataAccessException;
use LocalHalPH34\ShareReports\Classes\entities\Report;
use LocalHalPH34\ShareReports\Classes\daos\ReportDAO;
use LocalHalPH34\ShareReports\Classes\controllers\ParentController;

/**
 * 部門情報管理に関するコントローラクラス。
 */
class ReportController extends ParentController
{
    /**
     * 部門情報リスト画面表示処理。
     */
    public function showReportList(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flashMessages = $this->flash->getMessages();
        if (isset($flashMessages)) {
            $assign["flashMsg"] = $this->flash->getFirstMessage("flashMsg");
        }
        $this->cleanSession();
        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $reportList = $reportDAO->findAll();
            $assign["rpList"] = $reportList;
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }

        $assign["name"] = $_SESSION["name"];
        $returnResponse = $this->view->render($response, "report/reportList.html", $assign);
        return $returnResponse;
    }

    public function goReportAdd(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $rcDAO = new ReportDAO($db);
            $rcList = $rcDAO->findCate();
            $assign["rcList"] = $rcList;
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }
        $assign["name"] = $_SESSION["name"];
        $returnResponse = $this->view->render($response, "report/reportAdd.html", $assign);
        return $returnResponse;
    }

    /**
     * 部門情報登録処理。
     */
    public function reportAdd(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $templatePath = "report/reportAdd.html";
        $isRedirect = false;
        $assign = [];

        $postParams = $request->getParsedBody();
        $addRpDate = $postParams["addYear"] . "-" . $postParams["addMonth"] . "-" . $postParams["addDate"];
        $addRpFrom = $postParams["addTime"] . ":" . $postParams["addMin"];
        $addRpTo = $postParams["addEndTime"] . ":" . $postParams["addEndMin"];
        $addRpId = $postParams["addRcId"];
        $addRpCn = $postParams["addRpCn"];
        $addUsId = $_SESSION["id"];
        date_default_timezone_set('Asia/Tokyo');
        $addCreate = date("Y-m-d H:i:s");
        $addRpCn = str_replace("　", "", $addRpCn);
        $addRpCn = trim($addRpCn);

        $share = new Report();
        $share->setRpDate($addRpDate);
        $share->setRpFrom($addRpFrom);
        $share->setRpTo($addRpTo);
        $share->setRpContent($addRpCn);
        $share->setRpId($addRpId);
        $share->setUsId($addUsId);
        $share->setRpAt($addCreate);

        $validationMsgs = [];

        if (empty($addRpCn)) {
            $validationMsgs[] = "作業内容の入力は必須です。";
        }

        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new reportDAO($db);

            if (empty($validationMsgs)) {
                $id = $reportDAO->insert($share);
                if ($id === -1) {
                    throw new
                        DataAccessException("情報登録に失敗しました。もう一度はじめからやり直してください。");
                } else {
                    $isRedirect = true;
                    $this->flash->addMessage("flashMsg", "レポートID" . $id . "でレポート情報を登録しました。");
                }
            } else {
                $assign["rplist"] = $share;
                $assign["validationMsgs"] = $validationMsgs;
                $assign["date"] = explode("-", $share->getRpDate());
                $assign["from"] = explode(":", $share->getRpFrom());
                $assign["to"] = explode(":", $share->getRpTo());
                $assign["name"] = $_SESSION["name"];
                try {
                    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
                    $rcDAO = new ReportDAO($db);
                    $rcList = $rcDAO->findCate();
                    $assign["rcList"] = $rcList;
                } catch (PDOException $ex) {
                    $exCode = $ex->getCode();
                    throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
                } finally {
                    $db = null;
                }

            }
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }

        if ($isRedirect) {
            $returnResponse = $response->withStatus(302)->withHeader("Location", "/ph34/sharereports/public/report/showReportList");
        } else {
            $returnResponse = $this->view->render($response, $templatePath, $assign);
        }
        return $returnResponse;
    }

    public function showReportDetail(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flashMessages = $this->flash->getMessages();
        if (isset($flashMessages)) {
            $assign["flashMsg"] = $this->flash->getFirstMessage("flashMsg");
        }
        $this->cleanSession();
        $id = $args["id"];
        $assign["name"] = $_SESSION["name"];
        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $reportList = $reportDAO->findByPK($id);
            $assign["rpList"] = $reportList;
            $assign["id"] = $id;
            $rpId = $reportList->getRpId();
            $usId = $reportList->getUsId();
            $usList = $reportDAO->findByUsName($usId);
            $rcList = $reportDAO->findCateName($rpId);
            $assign["rcList"] = $rcList;
            $assign["usList"] = $usList;
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }

        $returnResponse = $this->view->render($response, "report/reportDetail.html", $assign);
        return $returnResponse;
    }

    public function confirmReportDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $flashMessages = $this->flash->getMessages();
        if (isset($flashMessages)) {
            $assign["flashMsg"] = $this->flash->getFirstMessage("flashMsg");
        }
        $this->cleanSession();
        $id = $args["id"];
        $assign["name"] = $_SESSION["name"];
        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $reportList = $reportDAO->findByPK($id);
            $assign["rpList"] = $reportList;
            $assign["id"] = $id;
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, "report/reportDelete.html", $assign);
        return $returnResponse;
    }

    public function reportDelete(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $postParams = $request->getParsedBody();
        $deleteReportId = $postParams["deleteReportId"];
        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $result = $reportDAO->delete($deleteReportId);
            if ($result) {
                $this->flash->addMessage("flashMsg", "レポートID" . $deleteReportId . "のレポート情報を削除しました。");
            } else {
                throw new
                    DataAccessException("情報削除に失敗しました。もう一度はじめからやり直してください。");
            }
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }
        $returnResponse = $response->withStatus(302)->withHeader("Location", "/ph34/sharereports/public/report/showReportList");
        return $returnResponse;
    }

    public function prepareReportEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $templatePath = "report/reportEdit.html";
        $assign = [];
        $assign["name"] = $_SESSION["name"];
        $editReportId = $args["id"];
        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);
            $report = $reportDAO->findByPK($editReportId);
            if (empty($report)) {
                throw new DataAccessException("レポート情報の取得に失敗しました。");
            } else {
                $assign["rpList"] = $report;
                $assign["date"] = explode("-", $report->getRpDate());
                $assign["from"] = explode(":", $report->getRpFrom());
                $assign["to"] = explode(":", $report->getRpTo());

                try {
                    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
                    $rcDAO = new ReportDAO($db);
                    $rcList = $rcDAO->findCate();
                    $assign["rcList"] = $rcList;
                } catch (PDOException $ex) {
                    $exCode = $ex->getCode();
                    throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
                } finally {
                    $db = null;
                }

            }
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }
        $returnResponse = $this->view->render($response, $templatePath, $assign);
        return $returnResponse;
    }

    public function reportEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $templatePath = "report/reportEdit.html";
        $isRedirect = false;
        $assign = [];

        $postParams = $request->getParsedBody();
        $editId = $postParams["editId"];
        $editRpDate = $postParams["editYear"] . "-" . $postParams["editMonth"] . "-" . $postParams["editDate"];
        $editRpFrom = $postParams["editTime"] . ":" . $postParams["editMin"];
        $editRpTo = $postParams["editEndTime"] . ":" . $postParams["editEndMin"];
        $editRpId = $postParams["editRcId"];
        $editRpCn = $postParams["editRpCn"];
        $editUsId = $_SESSION["id"];
        $editCreate = date("Y-m-d H:i:s");
        $editRpCn = str_replace("　", "", $editRpCn);
        $editRpCn = trim($editRpCn);

        $share = new Report();
        $share->setId($editId);
        $share->setRpDate($editRpDate);
        $share->setRpFrom($editRpFrom);
        $share->setRpTo($editRpTo);
        $share->setRpContent($editRpCn);
        $share->setRpId($editRpId);
        $share->setUsId($editUsId);
        $share->setRpAt($editCreate);

        $validationMsgs = [];

        if (empty($editRpCn)) {
            $validationMsgs[] = "作業内容の入力は必須です。";
        }

        try {
            $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
            $reportDAO = new ReportDAO($db);

            if (empty($validationMsgs)) {
                $result = $reportDAO->update($share);
                if ($result) {
                    $isRedirect = true;
                    $this->flash->addMessage("flashMsg", "レポートID" . $editId . "でレポート情報を更新しました。");
                } else {
                    throw new DataAccessException("情報更新に失敗しました。もう一度はじめからやり直してください。");
                }
            } else {
                $assign["rpList"] = $share;
                $assign["validationMsgs"] = $validationMsgs;
                $assign["date"] = explode("-", $share->getRpDate());
                $assign["from"] = explode(":", $share->getRpFrom());
                $assign["to"] = explode(":", $share->getRpTo());
                $assign["name"] = $_SESSION["name"];
                try {
                    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
                    $rcDAO = new ReportDAO($db);
                    $rcList = $rcDAO->findCate();
                    $assign["rcList"] = $rcList;
                } catch (PDOException $ex) {
                    $exCode = $ex->getCode();
                    throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
                } finally {
                    $db = null;
                }
            }
        } catch (PDOException $ex) {
            $exCode = $ex->getCode();
            throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
        } finally {
            $db = null;
        }

        if ($isRedirect) {
            $returnResponse = $response->withStatus(302)->withHeader("Location", "/ph34/sharereports/public/report/showReportDetail/".$share->getId());
        } else {
            $returnResponse = $this->view->render($response, $templatePath, $assign);
        }
        return $returnResponse;
    }
}
