<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src03
 * 起動時の各種設定処理記述ファイル。
 *
 * @author Shinzo SAITO
 *
 * ファイル名=bootstrappers.php
 * フォルダ=/ph34/sharereports/
 */

use LocalHalPH34\ShareReports\Classes\exceptions\CustomErrorRenderer;

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer("text/html", CustomErrorRenderer::class);
