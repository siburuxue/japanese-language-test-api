<?php

namespace App\Repository;

use App\Entity\WxUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @extends ServiceEntityRepository<WxUser>
 *
 * @method WxUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method WxUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method WxUser[]    findAll()
 * @method WxUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WxUserRepository extends ServiceEntityRepository
{
    private ObjectManager $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WxUser::class);
        $this->manager = $registry->getManager();
    }

    public function insert(array $data)
    {
        $item = new WxUser();
        $item->setOpenId($data['openId']);
        $item->setUnionId($data['unionId']);
        $item->setToken($data['token']);
        $item->setUsername($data['username']);
        $item->setAvatar($data['avatarUrl']);
        $item->setGender($data['gender']);
        $item->setLanguage($data['language']);
        $item->setCity($data['city']);
        $item->setProvince($data['province']);
        $item->setCountry($data['country']);
        $item->setInsertTime(time());
        $this->manager->persist($item);
        $this->manager->flush();
        return $item->getId();
    }
}
