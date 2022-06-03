<?php

/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:48
 */

namespace FoodConfig\Service\Mail;

class MailTemplateMessage
{
    protected $renderer;

    public function setMailTemplate($template)
    {
        // Php render
        $resolver   = new \Laminas\View\Resolver\TemplateMapResolver();
        $resolver->setMap(array(
            'mailTemplate' => __DIR__ . '/../../../view/mail/' . $template . '.phtml'
        ));
        $this->renderer       = new \Laminas\View\Renderer\PhpRenderer();
        $this->renderer->setResolver($resolver);
        return $this;
    }

    public function getMailTemplate()
    {
        if (null === $this->renderer) {
            $this->setMailTemplate('mailTemplate');
        }
        return $this->renderer;
    }

    public function message(array $data = [])
    {
        $viewModel  = new \Laminas\View\Model\ViewModel();
        $viewModel->setTemplate('mailTemplate')->setVariables($data);

        $bodyPart = new \Laminas\Mime\Message();
        $bodyMessage = new \Laminas\Mime\Part($this->getMailTemplate()->render($viewModel));
        $bodyMessage->type = 'text/html';
        $bodyPart->setParts(array($bodyMessage));
        return $bodyPart;
    }
}
