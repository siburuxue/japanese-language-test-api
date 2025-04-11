<?php

namespace App\Entity;

use App\Repository\WxUserErrorAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'wx_user_error_answer')]
#[ORM\Entity(repositoryClass: WxUserErrorAnswerRepository::class)]
class WxUserErrorAnswer
{
    #[ORM\Column(name: "id")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private ?int $id = null;

    #[ORM\Column(name: "wx_user_id", options: ["comment" => "微信用户ID", "default" => 0])]
    private ?int $wxUserId = 0;

    #[ORM\Column(name: "paper_id", options: ["comment" => "题库ID", "default" => 0])]
    private ?int $paperId = 0;

    #[ORM\Column(name: "type", length: 255, nullable: true, options: ["comment" => "答案类型 listening writing reading language_usage"])]
    private ?string $type = null;

    #[ORM\Column(name: "wx_user_answer_id", options: ["comment" => "用户答题表ID", "default" => 0])]
    private ?int $wxUserAnswerId = 0;

    #[ORM\Column(name: "error_question_id", type: Types::JSON, nullable: true, options: ["comment" => "错题ID"])]
    private ?array $errorQuestionId = null;

    #[ORM\Column(name: "error_question_num", options: ["comment" => "错题数量", "default" => 0])]
    private ?int $errorQuestionNum = 0;

    #[ORM\Column(name: "insert_time", options: ["comment" => "添加时间", "default" => 0])]
    private ?int $insertTime = 0;

    #[ORM\Column(name: "update_time", options: ["comment" => "更新时间", "default" => 0])]
    private ?int $updateTime = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWxUserId(): ?int
    {
        return $this->wxUserId;
    }

    public function setWxUserId(int $wxUserId): static
    {
        $this->wxUserId = $wxUserId;

        return $this;
    }

    public function getPaperId(): ?int
    {
        return $this->paperId;
    }

    public function setPaperId(int $paperId): static
    {
        $this->paperId = $paperId;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getWxUserAnswerId(): ?int
    {
        return $this->wxUserAnswerId;
    }

    public function setWxUserAnswerId(int $wxUserAnswerId): static
    {
        $this->wxUserAnswerId = $wxUserAnswerId;

        return $this;
    }

    public function getErrorQuestionId(): ?array
    {
        return $this->errorQuestionId;
    }

    public function setErrorQuestionId(?array $errorQuestionId): static
    {
        $this->errorQuestionId = $errorQuestionId;

        return $this;
    }

    public function getErrorQuestionNum(): ?int
    {
        return $this->errorQuestionNum;
    }

    public function setErrorQuestionNum(int $errorQuestionNum): static
    {
        $this->errorQuestionNum = $errorQuestionNum;

        return $this;
    }

    public function getInsertTime(): ?int
    {
        return $this->insertTime;
    }

    public function setInsertTime(int $insertTime): static
    {
        $this->insertTime = $insertTime;

        return $this;
    }

    public function getUpdateTime(): ?int
    {
        return $this->updateTime;
    }

    public function setUpdateTime(int $updateTime): static
    {
        $this->updateTime = $updateTime;

        return $this;
    }
}
