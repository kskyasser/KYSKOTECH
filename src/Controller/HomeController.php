<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('home/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/catalogue', name: 'app_catalogue')]
    public function catalogue(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('home/catalogue.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/produit', name: 'app_produit')]
    public function produit(): Response
    {
        return $this->render('home/produit.html.twig');
    }

    #[Route('/panier', name: 'app_panier')]
    public function panier(): Response
    {
        return $this->render('home/panier.html.twig');
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(): Response
    {
        return $this->render('home/connexion.html.twig');
    }

    #[Route('/admin', name: 'app_admin')]
    public function admin(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('home/admin.html.twig', [
            'produits' => $produits,
        ]);
    }
}