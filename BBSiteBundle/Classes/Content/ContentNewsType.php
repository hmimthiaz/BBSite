<?php

namespace BlueBeetle\BBSiteBundle\Classes\Content;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormBuilder;
use Bellwether\BWCMSBundle\Classes\Constants\ContentFieldType;
use Bellwether\BWCMSBundle\Classes\Content\ContentType;
use Symfony\Component\Form\FormEvent;

use Bellwether\BWCMSBundle\Entity\ContentEntity;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Time;

class ContentNewsType Extends ContentType
{

    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);

        $this->setIsHierarchy(false);
        $this->setIsRootItem(false);

        $this->setIsSummaryEnabled(true);
        $this->setIsContentEnabled(true);
        $this->setIsUploadEnabled(false);

        $this->setIsSlugEnabled(true);
        $this->setIsIndexed(true);
        $this->setIsPageBuilderSupported(false);
        $this->setIsEventDateSupported(false);
        $this->setIsEventDateTime(false);

        $this->setIsPublishDateEnabled(true);
        $this->setIsExpireDateEnabled(false);
    }

    public function buildFields()
    {
        $this->addField('image', ContentFieldType::Content);

    }

    public function buildForm($isEditMode = false, ContentEntity $contentEntity = null)
    {
        $this->fb()->add('image', 'bwcms_content',
            array(
                'label' => 'Image',
                'contentType' => 'Media',
                'schema' => 'File',
                'onlyImage' => true,
                'required' => false,
                'constraints' => array()
            )
        );
    }

    public function addTemplates()
    {
        $this->addTemplate('Default', 'Default.html.twig', 'Default.png');
        $this->addTemplate('SuperMan', 'SuperMan.html.twig', 'Default.png');
    }

    public function validateForm(FormEvent $event)
    {

    }

    public function loadFormData(ContentEntity $content = null, Form $form = null)
    {
        return $form;
    }

    public function prepareEntity(ContentEntity $content = null, Form $form = null)
    {
        return $content;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return '@BWCMSBundle/Resources/icons/content/Page.png';
    }

    /**
     * @param ContentEntity $contentEntity
     * @return string|null
     */
    public function getPublicURL($contentEntity, $full = false)
    {
        $parameters = array(
            'slug' => $contentEntity->getSlug(),
            'siteSlug' => $contentEntity->getSite()->getSlug()
        );
        return $this->container->get('router')->generate('newsPage', $parameters, $full);
    }

    /**
     * @return null|RouteCollection
     */
    public function getRouteCollection()
    {
        $routes = new RouteCollection();
        $contentPageRoute = new Route('/{siteSlug}/news/{slug}.php', array(
            '_controller' => 'BBSiteBundle:Generic:newsPage',
        ), array(
            'siteSlug' => '[a-zA-Z0-9-]+',
            'slug' => '[a-zA-Z0-9-_]+'
        ));
        $routes->add('newsPage', $contentPageRoute);
        return $routes;
    }


    public function getType()
    {
        return "Content";
    }

    public function getSchema()
    {
        return "News";
    }

    public function getName()
    {
        return "News";
    }

}
