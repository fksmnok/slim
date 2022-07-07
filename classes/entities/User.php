<?php

/**
 * PH34 Sample14 マスタテーブル管理Slim版 Src12
 *
 * @author Shinzo SAITO
 *
 * ファイル名=User.php
 * フォルダ=/ph34/sharereports/classes/entities/
 */

namespace LocalHalPH34\ShareReports\Classes\entities;

/**
 *ユーザエンティティクラス。
 */
class User
{
    /**
     * 主キーのid。
     */
    private ?int $id = null;
    /**
     * ログインID。
     */
    private ?string $login = "";
    /**
     * パスワード。
     */
    private ?string $passwd = "";
    /**
     * 姓。
     */
    private ?string $name = "";
    /**
     * メールアドレス。
     */
    private ?string $mail = "";

    //以下アクセサメソッド。

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getLogin(): ?string
    {
        return $this->login;
    }
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }
    public function getPasswd(): ?string
    {
        return $this->passwd;
    }
    public function setPasswd(string $passwd): void
    {
        $this->passwd = $passwd;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    public function getMail(): ?string
    {
        return $this->mail;
    }
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }
}
