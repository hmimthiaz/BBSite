<?php

namespace BlueBeetle\BBSiteBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Bellwether\BWCMSBundle\Classes\Base\BaseService;

use Bellwether\BWCMSBundle\Classes\Event\RouteLoaderEvent;
use Bellwether\BWCMSBundle\Classes\Event\AdminMenuLoaderEvent;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use BlueBeetle\BBSiteBundle\Skins\Main\BBMainSkin;

use BlueBeetle\BBSiteBundle\Classes\Content\ContentNewsType;
use BlueBeetle\BBSiteBundle\Classes\Preference\HomePageType;



class BBCMSEventListener extends BaseService
{
    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);
    }

    function onSkinRegister()
    {
        $this->tp()->registerSkin(new BBMainSkin($this->container, $this->requestStack));
    }

    function onContentRegister()
    {

        $this->cm()->removeContentType('Widget', 'Folder');
        $this->cm()->removeContentType('Widget', 'HTML');
        $this->cm()->removeContentType('Taxonomy', 'Category');
        $this->cm()->removeContentType('Taxonomy', 'Tag');

        $this->cm()->registerContentType(new ContentNewsType($this->container, $this->requestStack));


    }

    function onPreferenceRegister()
    {
        $this->pref()->registerOptionType(new HomePageType($this->container, $this->requestStack));

    }


    function onRouteLoader(RouteLoaderEvent $event)
    {
        $homeRoute = $event->getRoutes()->get('home_page');
        $homeRoute->setDefault('_controller', 'BBSiteBundle:Generic:home');

    }

    function onAdminMenuLeft(AdminMenuLoaderEvent $event)
    {
//        $menuItem = $event->getMenuItem();
//
//        if ($this->isGranted('ROLE_AUTHOR')) {
//
//            $menuItem->addChild('Injazat', array('uri' => '#', 'label' => 'Injazat'))->setAttribute('dropdown', true);
//
//            $menuItem['Injazat']->addChild('SocialConnect', array(
//                'route' => '_admin_social_home',
//                'label' => 'Social Connect'
//            ));
//
//            $menuItem['Injazat']->addChild('Partner_Registration', array(
//                'route' => '_admin_partner_home',
//                'label' => 'Partner Registration'
//            ));
//
//            $menuItem['Injazat']->addChild('Vendor_Registration', array(
//                'route' => '_admin_vendor_home',
//                'label' => 'Vendor Registration'
//            ));
//
//
//
//
//            $event->moveItem('Injazat', 'Manage');
//        }


    }


}