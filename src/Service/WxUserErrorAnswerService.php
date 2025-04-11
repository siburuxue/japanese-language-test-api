<?php

namespace App\Service;

use App\Entity\WxUserErrorAnswer;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class WxUserErrorAnswerService
{
    private ObjectRepository $wxUserErrorAnswerRepository;

    public function __construct(
        private ManagerRegistry $doctrine,
    ) {
        $this->wxUserErrorAnswerRepository = $doctrine->getRepository(WxUserErrorAnswer::class);
    }

    public function update(array $array)
    {

    }

    public function insert(array $array)
    {

    }


}