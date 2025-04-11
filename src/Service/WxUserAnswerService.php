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
        private TestPaperService $testPaperService,
        private WxUserErrorAnswerService $wxUserErrorAnswerService,
    ) {
        $this->wxUserAnswerRepository = $doctrine->getRepository(WxUserAnswer::class);
    }

    public function answer(array $data): void
    {
        $paperInfo = $this->testPaperService->info($data['paperId']);
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
                $id = $this->part($item);
                $item['wxUserAnswerId'] = $id;
                // 判分
                $this->scoring($item, $paperInfo[$v]);
            }
        }else{
            $tmp = [];
            $tmp[$type] = $data['answer'][$type];
            $data['answer'] = $tmp;
            if(is_array($data['answerTime'])){
                $tmpAnswerTime = $data['answerTime'][$type];
                $data['answerTime'] = $tmpAnswerTime;
            }
            $id = $this->part($data);
            $data['wxUserAnswerId'] = $id;
            // 判分
            $this->scoring($data, $paperInfo[$type]);
        }
    }

    /**
     * 判分
     * @param array $data 答题内容
     * @param array $paper 试卷内容
     * @var $data1 $data = ['wxUserId' => 1, 'paperId' => 1, 'type'=> 'listening', 'answerId' => 1, answer = {"listening": {"1": "A", "2": "B", "3": "B", "4": "A", "5": "C"}}]
     * @var $data2 $data = ['wxUserErrorAnswerId' => 1, answer = {"listening": {"1": "A", "2": "B", "3": "B", "4": "A", "5": "C"}}];
     * @return void
     */
    public function scoring(array $data, array $paper)
    {
        if($data['type'] === Paper::LISTENING){
            $this->listeningScoring($data, $paper);
        }else if($data['type'] === Paper::READING){
            $this->readingScoring($data, $paper);
        }else if($data['type'] === Paper::LANGUAGE_USAGE){
            $this->languageUsageScoring($data, $paper);
        }else{
            $this->writingScoring($data, $paper);
        }
    }

    private function listeningScoring(array $data, array $paper)
    {
        $listeningMap = array_column($paper, "answer", "q_no");
        $errorQuestionId = [];
        $errorQuestionNum = 0;
        foreach ($data['listening'] as $k => $v) {
            if($v !== $listeningMap[$k]){
                $errorQuestionId[] = $k;
                $errorQuestionNum++;
            }
        }
        if($errorQuestionNum > 0){
            if(isset($data['wxUserErrorAnswerId'])){
                $this->wxUserErrorAnswerService->update([
                    'id' => $data['wxUserErrorAnswerId'],
                    'errorQuestionId' => $errorQuestionId,
                ]);
            }else{
                $this->wxUserErrorAnswerService->insert([
                    'wxUserId' => $data['wxUserId'],
                    'paperId' => $data['paperId'],
                    'type' => $data['type'],
                    'wxUserAnswerId' => $data['wxUserAnswerId'],
                    'errorQuestionId' => $errorQuestionId,
                    'errorQuestionNum' => $errorQuestionNum,
                ]);
            }
        }
    }

    private function readingScoring(array $data, array $paper)
    {
    }

    private function languageUsageScoring(array $data, array $paper)
    {
    }

    private function writingScoring(array $data, array $paper)
    {
    }

    private function part(array $data)
    {
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
            $id = $this->wxUserAnswerRepository->insert($data);
        }else{
            $id = $data['id'] = $answer->getId();
            $this->wxUserAnswerRepository->update($data);
        }
        return $id;
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