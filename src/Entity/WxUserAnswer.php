<?php

namespace App\Entity;

use App\Repository\WxUserAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'wx_user_answer')]
#[ORM\Index(name: 'I_wx_user_id', columns: ['wx_user_id'])]
#[ORM\Index(name: 'I_paper_id', columns: ['paper_id'])]
#[ORM\Index(name: 'I_answer_date', columns: ['insert_date'])]
#[ORM\Index(name: 'I_type', columns: ['type'])]
#[ORM\Entity(repositoryClass: WxUserAnswerRepository::class)]
class WxUserAnswer
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

    #[ORM\Column(name: "answer", type: Types::JSON, nullable: true, options: ["comment" => "学生答题内容"])]
    private ?array $answer = null;

    #[ORM\Column(name: "answer_num", options: ["comment" => "答题数", "default" => 0])]
    private ?int $answerNum = 0;

    #[ORM\Column(name: "answer_time", options: ["comment" => "学生作答时长 秒为单位", "default" => 0])]
    private ?int $answerTime = 0;

    #[ORM\Column(name: "insert_date", length: 255, options: ["comment" => "添加日期", "default" => ''])]
    private ?string $insertDate = '';

    #[ORM\Column(name: "insert_time", options: ["comment" => "添加时间", "default" => 0])]
    private ?int $insertTime = 0;

    #[ORM\Column(name: "update_time", options: ["comment" => "最后一次答题时间", "default" => 0])]
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

    public function getAnswer(): ?array
    {
        return $this->answer;
    }

    public function setAnswer(?array $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getAnswerNum(): ?int
    {
        return $this->answerNum;
    }

    public function setAnswerNum(int $answerNum): static
    {
        $this->answerNum = $answerNum;

        return $this;
    }

    public function getAnswerTime(): ?int
    {
        return $this->answerTime;
    }

    public function setAnswerTime(int $answerTime): static
    {
        $this->answerTime = $answerTime;

        return $this;
    }

    public function getInsertDate(): ?string
    {
        return $this->insertDate;
    }

    public function setInsertDate(string $insertDate): static
    {
        $this->insertDate = $insertDate;

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
