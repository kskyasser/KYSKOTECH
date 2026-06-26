<?php

namespace App\Controller;

use App\Document\Avis;
use App\Entity\Utilisateur;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AvisController extends AbstractController
{
    #[Route('/avis/init', name: 'app_avis_init')]
    public function init(DocumentManager $dm): Response
    {
        $avisData = [
            ['produitId' => 1, 'auteur' => 'Marie D.', 'note' => 5, 'commentaire' => 'Excellent telephone, livraison rapide.'],
            ['produitId' => 1, 'auteur' => 'Thomas L.', 'note' => 4, 'commentaire' => 'Bon rapport qualite-prix.'],
            ['produitId' => 2, 'auteur' => 'Sophie M.', 'note' => 5, 'commentaire' => 'MacBook parfait pour le travail.'],
            ['produitId' => 1, 'auteur' => 'Karim B.', 'note' => 5, 'commentaire' => 'Tres satisfait de mon achat.'],
        ];

        foreach ($avisData as $data) {
            $avis = new Avis();
            $avis->setProduitId($data['produitId']);
            $avis->setAuteur($data['auteur']);
            $avis->setNote($data['note']);
            $avis->setCommentaire($data['commentaire']);
            $avis->setDate(new \DateTime());
            $dm->persist($avis);
        }
        $dm->flush();

        return new Response('<h1>Avis de test crees dans MongoDB !</h1>');
    }

    #[Route('/init-admin', name: 'app_init_admin')]
    public function adminInit(EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $existant = $em->getRepository(Utilisateur::class)->findOneBy(['email' => 'admin@kyskotech.com']);
        if ($existant) {
            return new Response('<h1>Un admin existe deja avec cet email.</h1>');
        }

        $admin = new Utilisateur();
        $admin->setEmail('admin@kyskotech.com');
        $admin->setNom('Admin');
        $admin->setPrenom('KYSKOTECH');
        $admin->setRoles(['ROLE_ADMIN']);

        $motDePasseHache = $hasher->hashPassword($admin, 'admin123');
        $admin->setMotDePasse($motDePasseHache);

        $em->persist($admin);
        $em->flush();

        return new Response('<h1>Compte admin cree !</h1><p>Email : admin@kyskotech.com<br>Mot de passe : admin123</p>');
    }
}
