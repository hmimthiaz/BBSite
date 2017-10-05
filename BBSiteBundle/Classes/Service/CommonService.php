<?php

namespace BlueBeetle\BBSiteBundle\Classes\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Bellwether\BWCMSBundle\Classes\Base\BaseService;

use Bellwether\BWCMSBundle\Classes\Constants\ContentPublishType;

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;

class CommonService extends BaseService
{

    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);
        $this->init();
    }

    /**
     * @return CommonService
     */
    public function getManager()
    {
        return $this;
    }

    /**
     * Service Init.
     */
    public function init()
    {
        if (!$this->loaded) {
            //Hello
        }
        $this->loaded = true;
    }


    public function getLatestNews($ignoreIds = array(), $limit = 5)
    {
        $contentRepository = $this->getContentRepository();
        $qb = $contentRepository->getChildrenQueryBuilder(null, false);

        $qb->add('orderBy', 'node.publishDate DESC');

        $registeredContents = $this->cm()->getRegisteredContentTypes('Content', 'News');
        $condition = array();
        foreach ($registeredContents as $cInfo) {
            $condition[] = " (node.type = '" . $cInfo['type'] . "' AND node.schema = '" . $cInfo['schema'] . "' )";
        }
        if (!empty($condition)) {
            $qb->andWhere(' ( ' . implode(' OR ', $condition) . ' ) ');
        }

        if (!empty($ignoreIds)) {
            $notInId = array();
            foreach ($ignoreIds as $item) {
                $notInId[] = $item->getId();
            }
            $qb->andWhere($qb->expr()->notIn('node.id', $notInId));
        }

        $qb->andWhere(" node.site ='" . $this->sm()->getCurrentSite()->getId() . "' ");
        $qb->andWhere(" node.status ='" . ContentPublishType::Published . "' ");
        $qb->setFirstResult(0);
        $qb->setMaxResults($limit);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return \Bellwether\BWCMSBundle\Entity\ContentRepository
     */
    public function getContentRepository()
    {
        return $this->em()->getRepository('BWCMSBundle:ContentEntity');
    }



}
