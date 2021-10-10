<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProdFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }

    public function load(ObjectManager $manager)
    {
        // Création des comptes admins
        $this->loadAdmin($manager);

        // Exécution des requêtes.
        $manager->flush();
    }

    public function loadAdmin(ObjectManager $manager)
    {
        // Création d'un user avec des données constantes.
        // Ici il s'agit du compte admin.
        $user = new User();
        $user->setEmail('admin@example.com');
        // Hashage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        // Le format de la chaîne de caractères ROLE_FOO_BAR_BAZ
        // est libre mais il vaut mieux suivre la convention
        // proposée par Symfony.
        $user->setRoles(['ROLE_ADMIN']);

        // Demande d'enregistrement d'un objet dans la BDD.
        $manager->persist($user);
    }
}
