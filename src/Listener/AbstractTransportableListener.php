<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Listener;

use Zend\EventManager\EventInterface;
use Zend\Mail;

/**
 * Class AbstractTransportableListener
 * @package App\Listener
 */
abstract class AbstractTransportableListener
{
    /** @var Mail\Transport\TransportInterface */
    protected $transport;

    /**
     * UserCreatedListener constructor.
     * @param Mail\Transport\TransportInterface $transport
     */
    public function __construct(Mail\Transport\TransportInterface $transport)
    {
        $this->transport = $transport;
    }
}
