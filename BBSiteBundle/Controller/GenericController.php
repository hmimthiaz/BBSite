<?php

namespace BlueBeetle\BBSiteBundle\Controller;

use Bellwether\BWCMSBundle\Classes\Base\FrontEndControllerInterface;
use Bellwether\BWCMSBundle\Classes\Base\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormError;

use Bellwether\BWCMSBundle\Classes\Constants\ContentPublishType;

use Bellwether\Common\Pagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


use BWDigital\BWSiteBundle\Entity\ContactEntity;

class GenericController extends BaseController implements FrontEndControllerInterface
{

    public function homeAction(Request $request, $siteSlug)
    {
        $returnVar = array();
        $preference = $this->pref()->getAllPreferenceByType('HomePage');
        $returnVar['pref'] = $preference;

        $returnVar['pageTitle'] = 'Home';
        if (isset($preference['pageTitle']) && !empty($preference['pageTitle'])) {
            $returnVar['pageTitle'] = $preference['pageTitle'];
        }
        if (isset($preference['pageDescription']) && !empty($preference['pageDescription'])) {
            $returnVar['pageDescription'] = $preference['pageDescription'];
        }
        if (isset($preference['pageKeywords']) && !empty($preference['pageKeywords'])) {
            $returnVar['pageKeywords'] = $preference['pageKeywords'];
        }
        if (isset($preference['openGraphImage']) && !empty($preference['openGraphImage'])) {
            $returnVar['openGraphImage'] = $preference['openGraphImage'];
        }
        return $this->render($this->getTemplateName('Pages/Home.html.twig'), $returnVar);
    }


    public function newsPageAction(Request $request, $siteSlug, $slug)
    {
        $returnVar = array();
        $contentTypes = $this->cm()->getRegisteredContentTypes('Content', 'News');
        $contentEntity = $this->cq()->getContentBySlug($slug, null, $contentTypes);
        if (empty($contentEntity)) {
            throw new NotFoundHttpException('Page does not exist');
        }

        $pageMeta = $this->cm()->getContentAllMeta($contentEntity);

        $returnVar['pageTitle'] = $contentEntity->getTitle();
        if (isset($pageMeta['pageTitle']) && !empty($pageMeta['pageTitle'])) {
            $returnVar['pageTitle'] = $pageMeta['pageTitle'];
        }
        if (isset($pageMeta['pageDescription']) && !empty($pageMeta['pageDescription'])) {
            $returnVar['pageDescription'] = $pageMeta['pageDescription'];
        }
        if (isset($pageMeta['pageKeywords']) && !empty($pageMeta['pageKeywords'])) {
            $returnVar['pageKeywords'] = $pageMeta['pageKeywords'];
        }
        if (isset($pageMeta['openGraphImage']) && !empty($pageMeta['openGraphImage'])) {
            $returnVar['openGraphImage'] = $pageMeta['openGraphImage'];
        }
        $returnVar['content'] = $contentEntity;
        $template = $this->getContentTemplate($contentEntity);
        return $this->render($template, $returnVar);
    }

    /**
     * @return \BlueBeetle\BBSiteBundle\Classes\Service\CommonService
     */
    public function commonService()
    {
        return $this->container->get('Site.Common')->getManager();
    }


}
