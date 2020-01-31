<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:35
 */

namespace FoodConfig\Event;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use FoodConfig\Service\Mail\MailTemplateMessage;
use FoodConfig\Service\Mail\MailService;

class SendMailEvent implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;
    public function attach(EventManagerInterface $events, $priority = 100)
    {
        $shared  		   = $events->getSharedManager();
        $this->listeners[] = $shared->attach('FoodConfig', 'send_mail', [$this, 'onSendMail'], $priority);
        $this->listeners[] = $shared->attach('FoodConfig', 'send_mail_1', [$this, 'onSendMail_1'], $priority);
        $this->listeners[] = $shared->attach('FoodConfig', 'send_mail_2', [$this, 'onSendMail_2'], $priority);
    }
    public function onSendMail(EventInterface $e)
    {
        // set email template
        $mailTemplate = new MailTemplateMessage();
        $mailTemplate->setMailTemplate('forgotpassword');
        $data['body'] = $mailTemplate->message($e->getParams());
        $data['email'] = $_POST['email'];
        // mailmessage
        $mailMessage = new MailService();
        $mailMessage->setMail($data)->sendMail();
    }

    public function onSendMail_1(EventInterface $e)
    {
        // set email template
        $mailTemplate = new MailTemplateMessage();
        $mailTemplate->setMailTemplate('contact');
        $data['body'] = $mailTemplate->message($e->getParams());
        $data['email'] = ''; // edit
        // mailmessage
        $mailMessage = new MailService();
        $mailMessage->setMail($data)->sendMail();
    }

    public function onSendMail_2(EventInterface $e)
    {
        // set email template
        $mailTemplate = new MailTemplateMessage();
        $mailTemplate->setMailTemplate('shopping');
        $data['body'] = $mailTemplate->message($e->getParams());
        $data['email'] = $e->getParam('email');
        // mailmessage
        $mailMessage = new MailService();
        $mailMessage->setMail($data)->sendMail();
    }

}