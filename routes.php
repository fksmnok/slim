<?php
/**
* PH34 Sample14 マスタテーブル管理Slim版 Src04
* ルーティング情報記述ファイル。
*
* @author Shinzo SAITO
*
* ファイル名=routes.php
* フォルダ=/ph34/sharereports/
*/
use LocalHalPH34\ShareReports\Classes\middlewares\LoginCheck;
use LocalHalPH34\ShareReports\Classes\controllers\LoginController;
use LocalHalPH34\ShareReports\Classes\controllers\TopController;
use LocalHalPH34\ShareReports\Classes\controllers\ReportController;
$app->setBasePath("/ph34/sharereports/public");
$app->get("/", LoginController::class.":goLogin");
$app->post("/login", LoginController::class.":login");
$app->get("/logout", LoginController::class.":logout");
$app->get("/goTop", TopController::class.":goTop")->add(new LoginCheck());
$app->get("/report/showReportList", ReportController::class.":showReportList")->add(new LoginCheck());
$app->get("/report/goReportAdd", ReportController::class.":goReportAdd")->add(new LoginCheck());
$app->get("/report/showReportDetail/{id}", ReportController::class.":showReportDetail")->add(new LoginCheck());
$app->post("/report/reportAdd", ReportController::class.":reportAdd")->add(new LoginCheck());
$app->get("/dept/prepareDeptEdit/{dpId}", DeptController::class.":prepareDeptEdit")->add(new LoginCheck());
$app->post("/dept/deptEdit", DeptController::class.":deptEdit")->add(new LoginCheck());
$app->get("/dept/confirmDeptDelete/{rpList.dpId}", DeptController::class.":confirmDeptDelete")->add(new LoginCheck());
$app->post("/report/reportDelete", ReportController::class.":reportDelete")->add(new LoginCheck());
$app->get("/report/confirmReportDelete/{id}", ReportController::class.":confirmReportDelete")->add(new LoginCheck());
$app->post("/report/reportEdit", ReportController::class.":reportEdit")->add(new LoginCheck());
$app->get("/report/prepareReportEdit/{id}", ReportController::class.":prepareReportEdit")->add(new LoginCheck());
