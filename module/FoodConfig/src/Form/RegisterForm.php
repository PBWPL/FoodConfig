<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-11
 * Time: 17:49
 */

namespace FoodConfig\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

class RegisterForm extends Form
{
    function __construct() {
        parent::__construct('fregister');

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
        // Add "name" field
        $this->add([
            'type'  => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Imię',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Imię',
            ],
        ]);

        // Add "surname" field
        $this->add([
            'type'  => 'text',
            'name' => 'surname',
            'options' => [
                'label' => 'Nazwisko',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Nazwisko',
            ],
        ]);

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

        // Add "password_one" field
        $this->add([
            'type'  => 'password',
            'name' => 'password_one',
            'options' => [
                'label' => 'Hasło',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Hasło',
            ],
        ]);

        // Add "password_two" field
        $this->add([
            'type'  => 'password',
            'name' => 'password_two',
            'options' => [
                'label' => 'Powtórz hasło',
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Potwierdź hasło',
            ],
        ]);

        // Add "usershow" field
        $this->add([
            'name' => 'usershow',
            'type'  => 'checkbox',
            'options' => [
                'label' => 'Publiczny',
            ],
        ]);

        // Add "avatar" field
        $this->add([
            'name' => 'avatar',
            'type'  => 'file',
            'attributes' => [
                'id' => 'uploadImage',
                'onchange' => 'PreviewImage()'
            ],
            'options' => [
                'label' => 'Avatar',
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
                'value' => 'Zarejestruj',
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

        // Add input for "name" field
        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 2,
                        'max' => 60,
                    ],
                ],
            ],
        ]);

        // Add input for "surname" field
        $inputFilter->add([
            'name'     => 'surname',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
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

        // Add input for "password" field
        $inputFilter->add([
            'name'     => 'password_one',
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

        $inputFilter->add([
            'name'     => 'password_two',
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

        $inputFilter->add([
            'name'     => 'usershow',
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

        $inputFilter->add([
            'name'     => 'avatar',
            'required' => false,
            'filters'  => [
            ],
            'validators' => [
                ['name'    => 'FileUploadFile'],
                [
                    'name'    => 'FileMimeType',
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/png']
                    ]
                ],
                ['name' => 'FileSize'],
                [
                    'name' => 'FileIsImage',
                    'options' => [
                        'max' => 5000
                    ]
                ],
                [
                    'name'    => 'FileImageSize',
                    'options' => [
                        'minWidth'  => 128,
                        'minHeight' => 128,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
        ]);
    }
}