<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setBirthDate(\DateTime::createFromFormat('Y-m-d', "1988-01-09"));
        $user->setEmail('klojan@mail.ru');
        $user->setFirstName('Nikolai');
        $user->setGender(1);
        $user->setLastName('Pulkkinen');
        $user->setMiddleName('Anatoljevich');
        $user->setPhone('53506476');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'test2'
        ));
        $user->setUserStatus(false);
        $user->setApiToken('test');

        $user1 = new User();
        $user1->setBirthDate(\DateTime::createFromFormat('Y-m-d', "1988-01-09"));
        $user1->setEmail('klojan1@mail.ru');
        $user1->setFirstName('Vass');
        $user1->setGender(1);
        $user1->setLastName('Pulkkinen');
        $user1->setMiddleName('Anatoljevich');
        $user1->setPhone('53506476');
        $user1->setPassword($this->passwordEncoder->encodePassword(
            $user1,
            'test3'
        ));
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setUserStatus(false);
        $user1->setApiToken('test1');

        $apiToken1 = new ApiToken($user);
        $apiToken2 = new ApiToken($user1);

        $manager->persist($user);
        $manager->persist($user1);
        $manager->persist($apiToken1);
        $manager->persist($apiToken2);

        $manager->flush();
    }
}
