<?php

namespace App\Repository;

use App\Entity\WxUserErrorAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @extends ServiceEntityRepository<WxUserErrorAnswer>
 *
 * @method WxUserErrorAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method WxUserErrorAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method WxUserErrorAnswer[]    findAll()
 * @method WxUserErrorAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WxUserErrorAnswerRepository extends ServiceEntityRepository
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WxUserErrorAnswer::class);
        $this->manager = $registry->getManager();
    }

    public function insert(array $array)
    {
        $error = new WxUserErrorAnswer();
        $error->setWxUserId($array['wxUserId']);
        $error->setPaperId($array['paperId']);
        $error->setType($array['type']);
        $error->setWxUserAnswerId($array['wxUserAnswerId']);
        $error->setErrorQuestionId($array['errorQuestionId']);
        $error->setErrorQuestionNum($array['errorQuestionNum']);
        $error->setInsertTime(time());
        $error->setUpdateTime(time());
        $this->manager->persist($error);
        $this->manager->flush();
        return $error->getId();
    }

    public function update(array $array)
    {
        $error = $this->find($array['id']);
        $error->setErrorQuestionId($array['errorQuestionId']);
        $error->setUpdateTime(time());
        $this->manager->persist($error);
        $this->manager->flush();
        return $error->getId();
    }
}
