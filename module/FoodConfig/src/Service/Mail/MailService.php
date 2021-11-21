<?php
/**
 * Created by PhpStorm.
 * User: piotrbec
 * Date: 2019-04-03
 * Time: 14:48
 */

namespace FoodConfig\Service\Mail;

use Laminas\Mail\Transport\Smtp as SmtpTransport;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mail;

class MailService
{
    protected $transport;
    protected $mail;
    public function setTransport()
    {
        $options = new SmtpOptions([
            'name' => '', // edit
            'host' => '', // edit
            'port'     => 465,
            'connection_class' => 'login',
            'connection_config' => [
                'username' => '', // edit
                'password' => '', // edit
                'ssl'      => 'ssl'
            ],
        ]);
        $this->transport = new SmtpTransport;
        $this->transport->setOptions($options);
        return $this;
    }
    public function getTransport()
    {
        if (null === $this->transport) {
            $this->setTransport();
        }
        return $this->transport;
    }
    public function setMail($data = null)
    {
        //create and set email:
        $this->mail = new Mail\Message;
        $this->mail->setFrom('', 'Food Config');  // edit
        $this->mail->addTo($data['email'], 'Customer');
        $this->mail->setSubject("wiadomość została wygenerowana automatycznie");
        $this->mail->setBody($data['body']);
        return $this;
    }
    public function getMail()
    {
        if (null === $this->mail) {
            $this->setMail();
        }
        return $this->mail;
    }
    public function sendMail()
    {
        $this->getTransport()->send($this->getMail());
    }
}