<?php

namespace App\Controller;

use App\Document\Avis;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AvisController extends AbstractController
{
    #[Route('/avis/init', name: 'app_avis_init')]
    public function init(DocumentManager $dm): Response
    {
        // Quelques avis de test (produitId 1 = iPhone 15)
        $avisData = [
            ['produitId' => 1, 'auteur' => 'Marie D.', 'note' => 5, 'commentaire' => 'Excellent téléphone, livraison rapide. Je recommande KYSKOTECH !'],
            ['produitId' => 1, 'auteur' => 'Thomas L.', 'note' => 4, 'commentaire' => 'Bon rapport qualité-prix, conforme à la description.'],
            ['produitId' => 2, 'auteur' => 'Sophie M.', 'note' => 5, 'commentaire' => 'MacBook parfait pour le travail, très rapide.'],
            ['produitId' => 1, 'auteur' => 'Karim B.', 'note' => 5, 'commentaire' => 'Très satisfait de mon achat, rien à redire.'],
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

        return new Response('<h1>✅ Avis de test créés dans MongoDB !</h1><p>'.count($avisData).' avis ont été ajoutés. Tu peux maintenant les voir sur les fiches produits.</p>');
    }
}