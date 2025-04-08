<?php

namespace App\Repository;

use App\Entity\WxUserAnswer;
use App\Lib\Constant\Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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
        $answer->setAnswerNum($data['answerNum']);
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
        $answer->setAnswerNum($data['answerNum']);
        $this->manager->persist($answer);
        $this->manager->flush();
    }

    public function list(array $array): array
    {
        $db = $this->createQueryBuilder('t')->where("1 = 1");
        if(isset($array['wxUserId'])){
            $db->andWhere("t.wxUserId = :wxUserId")->setParameter("wxUserId", $array['wxUserId']);
        }
        if(isset($array['paperId'])){
            $db->andWhere("t.paperId = :paperId")->setParameter("paperId", $array['paperId']);
        }
        if(isset($array['type']) && !empty($array['type'])){
           $db->andWhere("t.type = :type")->setParameter("type", $array['type']);
        }
        return $db->getQuery()->getArrayResult();
    }

    /**
     * 连续答题天数统计
     * @param int $wxUserId 微信用户ID
     * @return mixed
     * @throws \Doctrine\DBAL\Exception
     */
    public function getConsecutiveAnsweringDays(int $wxUserId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = <<<EOF
select count(distinct insert_date) as num
from wx_user_answer
where insert_date > (select ifnull(max(C.insert_date_A),'1970-01-01')
             from (select A.insert_date as insert_date_A,
                          datediff(B.insert_date, A.insert_date) as diff
                   from (select insert_date, row_number() over (order by insert_date) as num
                         from (select distinct insert_date from wx_user_answer where wx_user_id = :wxUserId) l ) A
                      , (select insert_date, row_number() over (order by insert_date) as num
                         from (select distinct insert_date from wx_user_answer where wx_user_id = :wxUserId) l ) B
                   where B.num = 1 + A.num) C
             where C.diff > 1)  and wx_user_id = :wxUserId
EOF;
        $rs = $conn->executeQuery($sql, ['wxUserId' => $wxUserId])->fetchAllAssociative();
        return $rs[0]['num'];
    }

    /**
     * 所有答题数统计
     * @param int $wxUserId 微信用户ID
     * @return int
     */
    public function getAllAnswerCount(int $wxUserId): int
    {
        return $this->createQueryBuilder('t')
            ->where("t.wxUserId = :wxUserId")
            ->setParameter("wxUserId", $wxUserId)
            ->select('sum(t.answerNum)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * 每日任务统计
     * @param int $wxUserId 微信用户ID
     * @param string $date 当前日期
     * @return array|false
     * @throws Exception
     */
    public function dailyQuests(int $wxUserId, string $date): array|false
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = <<<EOF
select ifnull(sum(case when type = 'listening' then 1 else 0 end), 0) > 0     as listening
       ,ifnull(sum(case when type = 'writing' then 1 else 0 end), 0)  > 0       as writing
       ,ifnull(sum(case when type = 'reading' then 1 else 0 end), 0)  > 0       as reading
       ,ifnull(sum(case when type = 'language_usage' then 1 else 0 end), 0) > 0 as language_usage
        ,floor(ifnull(sum(answer_time), 0) / 60) as answer_time
from wx_user_answer
where wx_user_id = :wxUserId
and insert_date = :insertDate

EOF;
        return $conn->executeQuery($sql, ['wxUserId' => $wxUserId, 'insertDate' => $date])->fetchAssociative();
    }
}
