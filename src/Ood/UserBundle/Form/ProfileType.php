<?php

namespace Ood\UserBundle\Form;

use Ood\UserBundle\Entity\User;
use Ood\PictureBundle\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfileType
 *
 * @package Ood\UserBundle\Form
 */
class ProfileType extends AbstractType
{
    /**
     * @var bool
     */
    protected $hasRemovePhoto;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'lastname', TextType::class,
                [
                    'label'    => 'profile.lastname.label',
                    'required' => false
                ]
            )
            ->add(
                'firstname', TextType::class,
                [
                    'label'    => 'profile.firstname.label',
                    'required' => false
                ]
            )
            ->add(
                'photo', ImageType::class,
                [
                    'label'    => false,
                    'required' => false
                ]
            )
            ->add(
                'submit', SubmitType::class,
                ['label' => 'profile.submit.label']
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSubmit']
            )
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostSubmit']
            );
    }

    /**
     * @param OptionsResolver $resolver
     *
     * @throws \Symfony\Component\OptionsResolver\Exception\AccessException
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => User::class,
                'translation_domain' => 'application'
            ]
        );
    }

    /**
     *
     */
    public function getBlockPrefix()
    {
        return 'ood_user_bundle_profile_type';
    }


    /**
     * @param FormEvent $formEvent
     */
    public function onPreSubmit(FormEvent $formEvent)
    {
        $user = $formEvent->getData();

        if (empty($user['photo'])) {
            $this->hasRemovePhoto = true;
        }
    }

    /**
     * @param FormEvent $formEvent
     */
    public function onPostSubmit(FormEvent $formEvent)
    {
        $user = $formEvent->getData();

        if ($this->hasRemovePhoto) {
            $user->setPhoto(null);
        }
    }
}
