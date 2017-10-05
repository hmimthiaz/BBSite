<?php

namespace BlueBeetle\BBSiteBundle\Skins\Main;

use Bellwether\BWCMSBundle\Classes\Base\BaseSkin;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BBMainSkin extends BaseSkin
{

    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);
    }

    public function get404Template()
    {
        return $this->getTemplateName("Extras/404.html.twig");
    }

    public function getSearchTemplate()
    {
        return $this->getTemplateName("Landing/Search/Search.html.twig");
    }

    public function initDefaultThumbStyles()
    {

        $this->addThumbStyleDefault('leadership_team_image', 'leadership_team_image', 'zoomCrop', 200, 200);

    }

    public function getNavigationRoutes()
    {
        $routes = array();
        $routes['home_page'] = 'Home';
        return $routes;
    }

    public function getNavigationRoute($routeName)
    {
        $routeParams = array(
            'siteSlug' => $this->sm()->getCurrentSite()->getSlug()
        );
        return $this->generateUrl($routeName, $routeParams);
    }

    public function getName()
    {
        return "Blue Beetle Skin";
    }
}