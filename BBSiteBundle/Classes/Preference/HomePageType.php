<?php

namespace BlueBeetle\BBSiteBundle\Classes\Preference;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilder;
use Bellwether\BWCMSBundle\Classes\Preference\PreferenceType;
use Bellwether\BWCMSBundle\Classes\Constants\PreferenceFieldType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;

use BlueBeetle\BBSiteBundle\Classes\Components\LeaderFormType;


class HomePageType Extends PreferenceType
{

    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);
        $this->setIsPagePreference(true);
        $this->setIsSeoFieldsEnabled(true);
        $this->setIsIndexed(true);

    }

    protected function buildFields()
    {
        $this->addField('sectionNewsTitle', PreferenceFieldType::String);
        $this->addField('sectionNewsDesc', PreferenceFieldType::String);
        $this->addField('leaders', PreferenceFieldType::Custom);

    }

    protected function buildForm()
    {

        $this->fb()->add('leaders', 'bwcms_collection',
            array(
                'label' => 'Leaders',
                'type' => new LeaderFormType(),
                'allow_add' => true
            )
        );

        $this->fb()->add('sectionNewsTitle', 'text',
            array(
                'label' => 'Caption',
                'required' => false,
            )
        );

        $this->fb()->add('sectionNewsDesc', 'textarea',
            array(
                'required' => false,
                'label' => 'Content',
                'attr' => array(
                    'class' => 'editor'
                )
            )
        );

    }

    function validateForm(FormEvent $event)
    {

    }

    public function loadCustomField($fieldName, $fieldValue)
    {
        if ($fieldName == 'leaders') {
            if (!empty($fieldValue) && is_array($fieldValue)) {
                foreach ($fieldValue as $key => $value) {
                    if (isset($value['image']) && !empty($value['image'])) {
                        $fieldValue[$key]['image'] = $this->cm()->getContentRepository()->find($value['image']);
                    } else {
                        $fieldValue[$key]['image'] = null;
                    }
                }
            }
        }

        return $fieldValue;
    }

    public function getPublicURL($full = false)
    {
        $parameters = array(
            'siteSlug' => $this->sm()->getCurrentSite()->getSlug()
        );
        return $this->container->get('router')->generate('home_page', $parameters, $full);
    }

    public function getType()
    {
        return 'HomePage';
    }

    public function getName()
    {
        return "Home Page";
    }

}