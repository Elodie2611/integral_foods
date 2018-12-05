<?php

namespace App\DataFixtures;

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
           $user-> setEmail('admin@admin.fr');
           $user-> setRoles(array("roles"=>'ROLE_ADMIN'));
           $user->setPassword($this->passwordEncoder->encodePassword(
             $user,
             'admin'
         ));
           $manager->persist($user);
           $user2 = new User(); 
           $user2-> setEmail('user@user.fr');
           $user2-> setRoles(array("roles"=>'ROLE_USER'));
           $user2->setPassword($this->passwordEncoder->encodePassword(
             $user2,
             'user'
         ));
           $manager->persist($user2);

        $manager->flush();
    }
}
