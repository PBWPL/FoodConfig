<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:40
 */

namespace FoodConfig\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

class LoginForm extends Form
{
    function __construct() {
        parent::__construct('flogin');

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
        // Add "email" field
        $this->add([
            'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'E-mail',
            ],
        ]);

        // Add "password" field
        $this->add([
            'type'  => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Hasło',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Hasło',
                'autocomplete' => 'on'
            ],
        ]);
        // Add "rememberMe" field
        $this->add([
            'type' => 'checkbox',
            'name'  => 'remember_me',
            'options' => [
                'label' => 'Nie wylogowuj mnie'
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
                'value' => 'Zaloguj',
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

        // Add input for "password" field
        $inputFilter->add([
            'name'     => 'password',
            'required' => true,
            'filters'  => [
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64
                    ],
                ],
            ],
        ]);
        // Add input for "remember_me" field
        $inputFilter->add([
            'name'     => 'remember_me',
            'required' => false,
            'filters'  => [
            ],
            'validators' => [
                [
                    'name'    => 'InArray',
                    'options' => [
                        'haystack' => [0, 1],
                    ]
                ],
            ],
        ]);
    }
}
