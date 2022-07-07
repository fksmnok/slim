<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src11
 *
 * @author Shinzo SAITO
 *
 * ファイル名=Dept.php
 * フォルダ=/ph34/sharereports/classes/entities/
 */

namespace LocalHalPH34\ShareReports\Classes\entities;

/**
 *部門エンティティクラス。
 */
class Report
{
    /**
     * ID。
     */
    private ?int $id = null;
    /**
     * 部門番号。
     */
    private ?string $rpDate = "";
    /**
     * 部門名。
     */
    private ?string $rpFrom = "";
    /**
     * 所在地
     */
    private ?string $rpTo = "";

    private ?string $rpContent = "";

    private ?string $rpAt = "";

    private ?int $rpId = null;

    private ?int $usId = null;

    private ?int $rcId = null;

    private ?string $rcName = "";

    private ?string $usName = "";

    private ?string $usMail = "";

    //以下アクセサメソッド。

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getRpDate(): ?string
    {
        return $this->rpDate;
    }
    public function setRpDate(string $rpDate): void
    {
        $this->rpDate = $rpDate;
    }
    public function getRpFrom(): ?string
    {
        return $this->rpFrom;
    }
    public function setRpFrom(string $rpFrom): void
    {
        $this->rpFrom = $rpFrom;
    }
    public function getRpTo(): ?string
    {
        return $this->rpTo;
    }
    public function setRpTo(?string $rpTo): void
    {
        $this->rpTo = $rpTo;
    }
    public function getRpContent(): ?string
    {
        return $this->rpContent;
    }
    public function setRpContent(?string $rpContent): void
    {
        $this->rpContent = $rpContent;
    }
    public function getRpAt(): ?string
    {
        return $this->rpAt;
    }
    public function setRpAt(?string $rpAt): void
    {
        $this->rpAt = $rpAt;
    }
    public function getRpId(): ?int
    {
        return $this->rpId;
    }
    public function setRpId(?int $rpId): void
    {
        $this->rpId = $rpId;
    }
    public function getUsId(): ?int
    {
        return $this->usId;
    }
    public function setUsId(?int $usId): void
    {
        $this->usId = $usId;
    }
    public function getRcId(): ?int
    {
        return $this->rcId;
    }
    public function setRcId(?int $rcId): void
    {
        $this->rpId = $rcId;
    }
    public function getRcName(): ?string
    {
        return $this->rcName;
    }
    public function setRcName(?string $rcName): void
    {
        $this->rcName = $rcName;
    }

    public function getUsName(): ?string
    {
        return $this->usName;
    }
    public function setUsName(?string $usName): void
    {
        $this->usName = $usName;
    }

    public function getUsMail(): ?string
    {
        return $this->usMail;
    }
    public function setUsMail(?string $usMail): void
    {
        $this->usMail = $usMail;
    }
    
}
