<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src17
 *
 * @author Shinzo SAITO
 *
 * ファイル名=TopController.php
 * フォルダ=/ph34/sharereports/classes/controllers/
 */

namespace LocalHalPH34\ShareReports\Classes\controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use LocalHalPH34\ShareReports\Classes\controllers\ParentController;

/**
 * Topに関するコントローラクラス。
 */
class TopController extends ParentController
{
    /**
     * トップ画面表示処理。
     */
    public function goTop(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->cleanSession();
        $returnResponse = $this->view->render($response, "top.html");
        return $returnResponse;
    }
}
