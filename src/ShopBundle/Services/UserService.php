<?php
/**
 * Created by PhpStorm.
 * User: macbookpro
 * Date: 09/12/2017
 * Time: 14:50
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\User;
use Symfony\Component\DependencyInjection\Container;

class UserService
{
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->doctrine = $container->get('doctrine');
        $this->em = $this->doctrine->getManager();
    }

    public function checkParams($password,$email,$username){
        if(!preg_match(User::getRegex(),$password))
            return "Invalid password, at less 8 caracters min, maj & number.";
        if($result = $this->checkEmail($email))
            return $result;
        if($this->checkUsername($username))
            return "Username already used";
        return false;
    }
    public function registerUser($password,$email,$username){
        $jwtService = $this->container->get('shop.jwt');
        $user = new User();
        $user->setUsername($username);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder(User::class);
        $user->setPassword($encoder->encodePassword($password,$user->getSalt()));
        $user->setEmail($email);
        $this->em->persist($user);
        $this->em->flush($user);
        return $jwtService->generateToken(['id' => $user->getId()]);

    }

    public function getUserId($username,$password){
        $userRepo = $this->doctrine->getRepository(User::class);
        $user = $userRepo->findOneBy(["username"=>$username]);
        if($user && password_verify($password,$user->getPassword()))
            return $user->getId();
    }

    private function checkEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
             return "invalid email.";
        $userRepo = $this->doctrine->getRepository(User::class);
        if($userRepo->findOneBy(["email"=>$email]))
             return "Email already used";
    }

    private function checkUsername($username)
    {
        $userRepo = $this->doctrine->getRepository(User::class);
        return $userRepo->findOneBy(["username"=>$username]);
    }
}