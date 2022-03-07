<?php
/* 
namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserEntityTest extends KernelTestCase {
    public function getUser(): User{
        return (new User())
        ->setFirstname('John')
        ->setLastname('Doe')
        ->setEmail('john@doe.fr')
        ->setAdresse('kjgjldghjdglhdflhdfhldfvld')
        ->setPassword('azerty')
        ->setTel('12345644664')
        ->setRoles(['ROLE_USER'])
        ;
    }
    public function testValidUser(Validator $validator){
        $user = $this->getUser();
        $errors = $validator->val;
        
        $this->assertEmpty($errors,"error : ".print_r($errors)); 
        
    }
}  */