<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\InputFilter;

use App\Entity\User;
use App\InputFilter\SignUpInputFilter;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\ArrayUtils;

/**
 * Class SignUpInputFilterTest
 * @package AppTest\InputFilter
 */
class SignUpInputFilterTest extends TestCase
{
    /** @var ObjectProphecy|ObjectManager */
    protected $objectManager;

    /**
     * @constructor
     */
    protected function setUp()
    {
        $this->objectManager =  $this->prophesize(EntityManagerInterface::class);

    }

    public function testEmptyRequest()
    {
        /** @var ObjectProphecy|ObjectRepository $repository */
        $repository = $this->prophesize(UserRepository::class);
        $this->objectManager
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var InputFilter $inputFilter */
        $inputFilter = new SignUpInputFilter($this->objectManager->reveal());
        $this->assertFalse($inputFilter->setData(['email' => '', 'password' => ''])->isValid());
    }

    public function testEmptyEmail()
    {
        /** @var array $values */
        $values = ['email' => ''];

        /** @var ObjectProphecy|ObjectRepository $repository */
        $repository = $this->prophesize(UserRepository::class);
        $repository
            ->findOneBy($values)
            ->willReturn(null);

        $this->objectManager
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var InputFilter $inputFilter */
        $inputFilter = new SignUpInputFilter($this->objectManager->reveal());
        $this->assertFalse($inputFilter->setData(ArrayUtils::merge([
            'password' => 12345678
        ], $values))->isValid());
    }

    public function testEmptyPassword()
    {
        /** @var array $values */
        $values = ['email' => 'demo@example.com'];

        /** @var ObjectProphecy|ObjectRepository $repository */
        $repository = $this->prophesize(UserRepository::class);
        $repository
            ->findOneBy($values)
            ->willReturn(new User);

        $this->objectManager
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var InputFilter $inputFilter */
        $inputFilter = new SignUpInputFilter($this->objectManager->reveal());
        $this->assertFalse($inputFilter->setData(ArrayUtils::merge([
            'password' => null
        ], $values))->isValid());
    }

    public function testUserExists()
    {
        /** @var array $values */
        $values = ['email' => 'demo@example.com'];

        /** @var ObjectProphecy|ObjectRepository $repository */
        $repository = $this->prophesize(UserRepository::class);
        $repository
            ->findOneBy($values)
            ->willReturn(new User);

        $this->objectManager
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var InputFilter $inputFilter */
        $inputFilter = new SignUpInputFilter($this->objectManager->reveal());
        $this->assertFalse($inputFilter->setData(ArrayUtils::merge([
            'password' => 12345678
        ], $values))->isValid());
    }

    public function testSuccess()
    {
        /** @var array $values */
        $values = ['email' => 'demo@example.com'];

        /** @var ObjectProphecy|ObjectRepository $repository */
        $repository = $this->prophesize(UserRepository::class);
        $repository
            ->findOneBy($values)
            ->willReturn(null);

        $this->objectManager
            ->getRepository(User::class)
            ->willReturn($repository);

        /** @var InputFilter $inputFilter */
        $inputFilter = new SignUpInputFilter($this->objectManager->reveal());
        $this->assertTrue($inputFilter->setData(ArrayUtils::merge([
            'password' => 12345678
        ], $values))->isValid());
    }
}
