<?php

namespace App\Entity;

use App\Repository\WxUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'wx_user')]
#[ORM\Index(name: 'I_username', columns: ['username'])]
#[ORM\Entity(repositoryClass: WxUserRepository::class)]
class WxUser
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "open_id", length: 255, options: ["default" => ''])]
    private ?string $openId = '';

    #[ORM\Column(name: "union_id", length: 255, options: ["default" => ''])]
    private ?string $unionId = '';

    #[ORM\Column(name: "token", length: 255, options: ["comment" => "登录token", "default" => ''])]
    private ?string $token = '';

    #[ORM\Column(name: "username", length: 255, options: ["comment" => "用户名", "default" => ''])]
    private ?string $username = '';

    #[ORM\Column(name: "avatar", length: 255, options: ["comment" => "头像", "default" => ''])]
    private ?string $avatar = '';

    #[ORM\Column(name: "gender", options: ["comment" => "性别 0未知 1男 2女", "default" => 0])]
    private ?int $gender = 0;

    #[ORM\Column(name: "language", length: 255, nullable: true, options: ["comment" => "语言", "default" => ''])]
    private ?string $language = '';

    #[ORM\Column(name: "city", length: 255, options: ["comment" => "城市", "default" => ''])]
    private ?string $city = '';

    #[ORM\Column(name: "province", length: 255, options: ["comment" => "省份", "default" => ''])]
    private ?string $province = '';

    #[ORM\Column(name: "country", length: 255, options: ["comment" => "国家", "default" => ''])]
    private ?string $country = '';

    #[ORM\Column(name: "insert_time", nullable: true, options: ["comment" => "添加时间"])]
    private ?int $insertTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): static
    {
        $this->openId = $openId;

        return $this;
    }

    public function getUnionId(): ?string
    {
        return $this->unionId;
    }

    public function setUnionId(string $unionId): static
    {
        $this->unionId = $unionId;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(int $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getInsertTime(): ?int
    {
        return $this->insertTime;
    }

    public function setInsertTime(?int $insertTime): static
    {
        $this->insertTime = $insertTime;

        return $this;
    }
}
