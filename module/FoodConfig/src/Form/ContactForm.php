<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-05-09
 * Time: 19:43
 */

namespace FoodConfig\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Captcha\ReCaptcha;
use ZendService\ReCaptcha\ReCaptcha as ReCaptchaService;

class ContactForm extends Form
{
    protected $entityManager;
    protected $recapcha;
    function __construct($entityManager, $recapchaKey)
    {
        parent::__construct('fcontact');
        $this->entityManager = $entityManager;
        $this->setRecapCha($recapchaKey);

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * set recapcha
     */
    public function setRecapCha($recapchaKey)
    {
        // zendservice recapcha
        $recaptchaService = new ReCaptchaService(
            $recapchaKey['public_key'],
            $recapchaKey['private_key']
        );
        // zend recapcha
        $recapcha = new ReCaptcha;
        $recapcha->setService($recaptchaService);
        $this->recapcha = $recapcha;
        return $this;
    }

    /**
     * get recapcha
     */
    public function getRecapCha()
    {
        return $this->recapcha;
    }


    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        // Add "email" field
        $this->add([
            'type'  => 'email',
            'name' => 'email',
            'options' => [
                'label' => 'Email:',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        // Add "message" field
        $this->add([
            'type'  => 'textarea',
            'name' => 'message',
            'options' => [
                'label' => 'Wiadomość:',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        // Add "recapcha" field
        $this->add([
            'type' => \Zend\Form\Element\Captcha::class,
            'name' => 'captcha',
            'options' => [
                'label' => 'Udowodnij, że nie jesteś robotem:',
                'captcha' => $this->getRecapCha(),
            ],
        ]);
        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ],
        ]);

        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Wyślij',
                'id' => 'submit',
                'class' => 'btn btn-lg btn-primary btn-block text-uppercase',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {
        // Create main input filter
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        // Add input for "message" field
        $inputFilter->add([
            'name'     => 'message',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 10
                    ],
                ],
            ],
        ]);

        // Add input for "email" field
        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'domain' => false
                ]
            ],
        ]);
    }

}