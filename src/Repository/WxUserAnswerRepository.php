<?php

namespace App\Repository;

use App\Entity\WxUserAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @extends ServiceEntityRepository<WxUserAnswer>
 *
 * @method WxUserAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method WxUserAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method WxUserAnswer[]    findAll()
 * @method WxUserAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WxUserAnswerRepository extends ServiceEntityRepository
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WxUserAnswer::class);
        $this->manager = $registry->getManager();
    }

    public function insert(array $data): ?int
    {
        $answer = new WxUserAnswer();
        $answer->setPaperId($data['paperId']);
        $answer->setWxUserId($data['wxUserId']);
        $answer->setType($data['type']);
        $answer->setAnswer($data['answer']);
        $answer->setAnswerTime($data['answerTime']);
        $answer->setInsertDate(date('Y-m-d'));
        $answer->setInsertTime(time());
        $answer->setUpdateTime(time());
        $this->manager->persist($answer);
        $this->manager->flush();
        return $answer->getId();
    }

    public function update(array $data): void
    {
        $answer = $this->find($data['id']);
        $answer->setAnswer($data['answer']);
        $answer->setType($data['type']);
        $answer->setUpdateTime(time());
        $this->manager->persist($answer);
        $this->manager->flush();
    }
}
