<?php

namespace App\Service;

use App\Entity\WxUserAnswer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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