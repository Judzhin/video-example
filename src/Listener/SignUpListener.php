<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Listener;

use Zend\EventManager\EventInterface;
use Zend\Mail;

/**
 * Class SignUpListener
 * @package App\Listener
 */
class SignUpListener extends AbstractTransportableListener
{
    /**
     * @param EventInterface $event
     */
    public function __invoke(EventInterface $event): void
    {
        /** @var Mail\Message $mail */
        $mail = (new Mail\Message)
            ->setBody('This is the text of the email.')
            ->setFrom('Freeaqingme@example.org', "Sender's name")
            ->addTo('Matthew@example.com', 'Name of recipient')
            ->setSubject('TestSubject');

        $this->transport->send($mail);
    }
}
