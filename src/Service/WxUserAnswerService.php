<?php

namespace App\Service;

use App\Entity\WxUserAnswer;
use App\Lib\Constant\Paper;
use App\Lib\Tool\ArrayTool;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class WxUserAnswerService
{
    private ObjectRepository $wxUserAnswerRepository;

    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        $this->wxUserAnswerRepository = $doctrine->getRepository(WxUserAnswer::class);
    }

    public function answer(array $data): void
    {
        $type = $data['type'];
        if(empty($type)){
            foreach (Paper::TYPE as $v) {
                $tmp = [];
                $tmp[$v] = $data['answer'][$v];
                $item = [
                    'wxUserId' => $data['wxUserId'],
                    'paperId' => $data['paperId'],
                    'type' => $v,
                    'answerTime' => $data['answerTime'][$v],
                    'answer' => $tmp
                ];
                $this->part($item);
            }
        }else{
            $tmp = [];
            $tmp[$type] = $data['answer'][$type];
            $data['answer'] = $tmp;
            if(is_array($data['answerTime'])){
                $tmpAnswerTime = $data['answerTime'][$type];
                $data['answerTime'] = $tmpAnswerTime;
            }
            $this->part($data);
        }
    }

    private function part(array $data){
        $type = $data['type'];
        $answer = $this->wxUserAnswerRepository->findOneBy([
            'wxUserId' => $data['wxUserId'],
            'paperId' => $data['paperId'],
            'type' => $type,
        ]);
        $answerNum = 0;
        if($type !== Paper::LANGUAGE_USAGE){
            // listening, writing, reading 计算完成题目数量
            foreach ($data['answer'][$type] as $datum) {
                if(!empty($datum)){
                    $answerNum++;
                }
            }
        }else{
            // language_usage 计算完成题目数量
            foreach ($data['answer'][$type]['part1'] as $datum) {
                if(!empty($datum)){
                    $answerNum++;
                }
            }
            foreach ($data['answer'][$type]['part2'] as $datum) {
                if(!empty($datum)){
                    $answerNum++;
                }
            }
        }
        $data['answerNum'] = $answerNum;
        if(empty($answer)){
            $this->wxUserAnswerRepository->insert($data);
        }else{
            $data['id'] = $answer->getId();
            $this->wxUserAnswerRepository->update($data);
        }
    }

    public function getAnswer(int $wxUserId, int $paperId, string $type)
    {
        $rs = $this->wxUserAnswerRepository->list([
            'wxUserId' => $wxUserId,
            'paperId' => $paperId,
            'type' => $type,
        ]);
        if(empty($type)){
            if(!empty($rs)){
                $tmp = [];
                foreach ($rs as $v) {
                    $tmp += $v['answer'];
                }
                return $tmp;
            }else{
                return ArrayTool::getEmptyAssociativeArray();
            }
        }else{
            if(!empty($rs)){
                return $rs[0]['answer'];
            }else{
                return ArrayTool::getEmptyAssociativeArray();
            }
        }
    }

    /**
     * 连续答题天数统计
     * @param int $wxUserId 微信用户ID
     * @return mixed
     * @throws \Doctrine\DBAL\Exception
     */
    public function getConsecutiveAnsweringDays(int $wxUserId)
    {
        return $this->wxUserAnswerRepository->getConsecutiveAnsweringDays($wxUserId);
    }

    /**
     * 所有答题数统计
     * @param int $wxUserId 微信用户ID
     * @return int
     */
    public function getAllAnswerCount(int $wxUserId): int
    {
        return $this->wxUserAnswerRepository->getAllAnswerCount($wxUserId);
    }

    /**
     * 每日任务统计
     * @param int $wxUserId 微信用户ID
     * @param string $date 当前日期
     * @return array|false
     * @throws Exception
     */
    public function getDailyQuests(int $wxUserId, string $date): false|array
    {
        return $this->wxUserAnswerRepository->dailyQuests($wxUserId, $date);
    }
}