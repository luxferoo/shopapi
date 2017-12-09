<?php
/**
 * Created by PhpStorm.
 * User: luxfero
 * Date: 10/18/17
 * Time: 10:42 AM
 */

namespace ShopBundle\Security;


use Doctrine\ORM\EntityManager;
use ShopBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{


    private $em;

    public function __construct(EntityManager $em)
    {

        $this->em = $em;
    }

    public function loadUserByUsername($auth_log)
    {
        $utilisateur = $this->em->getRepository(User::class);

        $user = $utilisateur->findOneBy(array("username" => $auth_log));

        if ($user)
            return $user;
        throw new UsernameNotFoundException('User doesn\'t exist :' . $user);
    }


    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function loadUserById($id)
    {
        $utilisateur = $this->em->getRepository(User::class);
        $user = $utilisateur->findOneBy(array("id" => $id));
        if ($user)
            return $user;
        return;
    }
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}