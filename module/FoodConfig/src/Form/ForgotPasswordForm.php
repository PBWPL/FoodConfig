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
use DoctrineModule\Validator\ObjectExists;
use Laminas\Captcha\ReCaptcha;
use Laminas\ReCaptcha\ReCaptcha as ReCaptchaService;
use FoodConfig\Entity\User;

class ForgotPasswordForm extends Form
{
    protected $entityManager;
    protected $recapcha;
    function __construct($entityManager, $recapchaKey)
    {
        parent::__construct('fforgot');
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
            'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Wpisz adres konta, do którego chcesz odzyskać dostęp:',
            ],
            'attributes' => [
                'class' => 'form-control',
            ],
        ]);
        // Add "recapcha" field
        $this->add([
            'type' => \Laminas\Form\Element\Captcha::class,
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
                'value' => 'Dalej',
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
                array(
                    'name' => ObjectExists::class,
                    'options' => array(
                        'object_repository' => $this->entityManager->getRepository(User::class),
                        'fields' => 'email'
                    )
                )
            ],
        ]);
    }
}