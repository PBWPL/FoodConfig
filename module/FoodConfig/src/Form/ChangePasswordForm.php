<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:41
 */

namespace FoodConfig\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

class ChangePasswordForm extends Form
{
    protected $entityManager;
    protected $recapcha;
    function __construct()
    {
        parent::__construct('fchangpw');
        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        $this->add([
            'type' => 'password',
            'name' => 'password',
            'attributes' => [
                'class' => 'form-control',
                'required' => 'required'
            ],
            'options' => [
                'label' => 'Hasło'
            ]
        ]);
        $this->add([
            'type' => 'password',
            'name' => 'repassword',
            'attributes' => [
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Hasło ponownie'
            ]
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
                'value' => 'Zmień hasło',
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
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filter' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags']
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => '6',
                        'max' => '50',
                    ]
                ],
            ]
        ]);

        $inputFilter->add([
            'name' => 'repassword',
            'required' => true,
            'filter' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags']
            ],
            'validators' => [
                [
                    'name' => 'Identical',
                    'options' => [
                        'token' => 'password',
                    ]
                ],
            ]
        ]);
    }
}