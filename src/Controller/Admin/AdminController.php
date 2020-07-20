<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Manager\PasswordManager;
use App\Repository\TextQuestionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends EasyAdminController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    private $textQuestionRepository;
    private $passwordManager;

    public function __construct(PasswordManager $passwordManager, UserPasswordEncoderInterface $passwordEncoder, TextQuestionRepository $textQuestionRepository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->textQuestionRepository = $textQuestionRepository;
        $this->passwordManager = $passwordManager;
    }

    public function persistUserEntity(User $user): void
    {
        $this->passwordManager->updatePassword($user);
        parent::persistEntity($user);
    }

    public function updateUserEntity(User $user): void
    {
        $this->passwordManager->updatePassword($user);
        parent::updateEntity($user);
    }
}
