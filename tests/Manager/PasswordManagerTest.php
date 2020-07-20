<?php

use App\Entity\User;
use App\Manager\PasswordManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordManagerTest extends TestCase
{
    public $passwordManagerTest;
    public $passwordEncoder;

    protected function setUp(): void
    {
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->passwordManagerTest = new PasswordManager($this->passwordEncoder);
    }

    public function testUpdatePassword(): void
    {
        $user = $this->createMock(User::class);

        $user
            ->method('getPlainPassword')
            ->willReturn('pass');

        $this->passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('ssap');

        $this->passwordManagerTest->updatePassword($user);
    }
}
