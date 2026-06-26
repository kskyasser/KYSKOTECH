<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(RequestStack $requestStack, ProduitRepository $produitRepository): Response
    {
        $session = $requestStack->getSession();
        $panier = $session->get('panier', []);

        $produitsPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite) {
            $produit = $produitRepository->find($id);
            if ($produit) {
                $produitsPanier[] = [
                    'produit' => $produit,
                    'quantite' => $quantite,
                    'sousTotal' => $produit->getPrix() * $quantite,
                ];
                $total += $produit->getPrix() * $quantite;
            }
        }

        return $this->render('panier/index.html.twig', [
            'produitsPanier' => $produitsPanier,
            'total' => $total,
        ]);
    }

    #[Route('/panier/ajouter/{id}', name: 'app_panier_ajouter')]
    public function ajouter(int $id, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $panier = $session->get('panier', []);

        // Si le produit est déjà dans le panier, on incrémente sa quantité
        if (isset($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/supprimer/{id}', name: 'app_panier_supprimer')]
    public function supprimer(int $id, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }
    #[Route('/panier/diminuer/{id}', name: 'app_panier_diminuer')]
    public function diminuer(int $id, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            $panier[$id]--;
            // Si la quantité tombe à 0, on retire le produit
            if ($panier[$id] <= 0) {
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('app_panier');
    }
}