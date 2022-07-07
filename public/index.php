<?php
/**
* PH34 Sample14 マスタテーブル管理Slim版 Src05
* 実行ファイル。
*
* @author Shinzo SAITO
*
* ファイル名=index.php
* フォルダ=/ph34/sharereports/public/
*/

use Slim\Factory\AppFactory;

require_once($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/vendor/autoload.php");
$app = AppFactory::create();

require_once($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/bootstrappers.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/ph34/sharereports/routes.php");

$app->run();
