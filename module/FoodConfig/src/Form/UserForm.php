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

class UserForm extends Form {
    protected $aclConfig;

    function __construct($aclConfig) {
        $this->aclConfig = $aclConfig;
        parent::__construct('user_form');
        $this->setAttribute('method', 'POST');
        $this->addElement();
        $this->addInputFilter();
    }
    public function addElement() {
        //add name form
        $this->add([
            'name' => 'name',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Imię'
            ]
        ]);

        //add usershow form
        $this->add([
            'name' => 'usershow',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Widoczność'
            ]
        ]);

        //add surname form
        $this->add([
            'name' => 'surname',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Nazwisko'
            ]
        ]);

        //add level form
        $this->add([
            'name' => 'level',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Level'
            ]
        ]);

        //add active form
        $this->add([
            'name' => 'active',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Aktywność'
            ]
        ]);

        //add name form
        $this->add([
            'name' => 'password',
            'type' => 'password',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Hasło'
            ]
        ]);

        //add avatar form
        $this->add([
            'name' => 'avatar',
            'type' => 'text',
            'attributes' => [
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Avatar'
            ]
        ]);

        //add email field
        $this->add([
            'name' => 'email',
            'type'  => 'email',
            'options' => [
                'label' => 'E-mail',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);

        //add submit form:
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'class' => 'btn btn-primary'
            ]
        ]);
    }
    public function addInputFilter() {
        //create new inputfilter:
        $input = new InputFilter();
        //set inputfilter in form:
        $this->setInputFilter($input);

        /** add input filter */

        $input->add([
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

        $input->add([
            'name'     => 'surname',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
            ],
        ]);

        $input->add([
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

        $input->add(array(
            'name' => 'usershow',
            'required' => true,
            'filters' => array(
                array('name'=>'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
            )
        ));

        $input->add([
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

        $input->add(array(
            'name' => 'active',
            'required' => true,
            'filters' => array(
                array('name'=>'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
            )
        ));

        $input->add(array(
            'name' => 'avatar',
            'required' => true,
            'filters' => array(
                array('name'=>'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
            )
        ));

        $input->add(array(
            'name' => 'level',
            'required' => true,
            'filters' => array(
                array('name'=>'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
            )
        ));

    }
}