<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Exception;

use MSBios\Exception\LogicException;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Response;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputInterface;

/**
 * Class ValidationException
 * @package App\Exception
 */
class ValidationException extends LogicException
{
    /** @var InputFilterInterface */
    protected $inputFilter;

    /**
     * ValidationException constructor.
     * @param InputFilterInterface $inputFilter
     */
    public function __construct(InputFilterInterface $inputFilter)
    {
        parent::__construct('Validation errors.');
        $this->inputFilter = $inputFilter;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->inputFilter->getInvalidInput();
    }

    /**
     * @return ResponseInterface
     */
    public function generateJsonResponse(): ResponseInterface
    {
        /** @var array $data */
        $data = [
            'name' => $this->getMessage(),
            'errors' => [
                // ...
            ]
        ];

        /** @var InputInterface $error */
        foreach ($this->inputFilter->getInvalidInput() as $name => $error) {
            $data['errors'][$name] = $error->getMessages();
        }

        return new JsonResponse($data, Response::STATUS_CODE_400);
    }
}
