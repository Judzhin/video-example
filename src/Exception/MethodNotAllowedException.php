<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Exception;

use MSBios\Exception\DomainException;
use Zend\Http\PhpEnvironment\Response;
use Zend\ProblemDetails\Exception\CommonProblemDetailsExceptionTrait;
use Zend\ProblemDetails\Exception\ProblemDetailsExceptionInterface;

/**
 * Class MethodNotAllowedException
 * @package App\Exception
 */
class MethodNotAllowedException extends DomainException implements ProblemDetailsExceptionInterface
{
    use CommonProblemDetailsExceptionTrait;

    /**
     * @param string $message
     * @return MethodNotAllowedException
     */
    public static function create(string $message) : self
    {
        /** @var self $e */
        $e = new self($message);
        $e->status = Response::STATUS_CODE_405;
        $e->detail = $message;
        $e->type = '/api/doc/method-not-allowed-error'; // TODO
        $e->title = 'Method is not allowed';
        return $e;
    }
}
