<?php

namespace App\Service;

use App\Entity\WxUserAnswer;
use App\Lib\Constant\Paper;
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
        $answer = $this->wxUserAnswerRepository->findOneBy([
            'wxUserId' => $data['wxUserId'],
            'paperId' => $data['paperId'],
            'type' => $data['type'],
        ]);
        if(empty($answer)){
            $this->wxUserAnswerRepository->insert($data);
        }else{
            $data['id'] = $answer->getId();
            $this->wxUserAnswerRepository->update($data);
        }
    }
}