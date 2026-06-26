<?php

namespace App\Controller;

use App\Document\Avis;
use App\Repository\CategorieRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProduitRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function catalogue(
        Request $request,
        ProduitRepository $produitRepository,
        CategorieRepository $categorieRepository,
        MarqueRepository $marqueRepository
    ): Response {
        $categorieId = $request->query->get('categorie');
        $marqueId = $request->query->get('marque');
        $prix = $request->query->get('prix');

        $criteres = [];
        if ($categorieId) {
            $criteres['categorie'] = $categorieId;
        }
        if ($marqueId) {
            $criteres['marque'] = $marqueId;
        }

        $produits = $produitRepository->findBy($criteres);

        if ($prix === 'moins500') {
            $produits = array_filter($produits, fn($p) => $p->getPrix() < 500);
        } elseif ($prix === '500a1000') {
            $produits = array_filter($produits, fn($p) => $p->getPrix() >= 500 && $p->getPrix() <= 1000);
        } elseif ($prix === 'plus1000') {
            $produits = array_filter($produits, fn($p) => $p->getPrix() > 1000);
        }

        return $this->render('home/catalogue.html.twig', [
            'produits' => $produits,
            'categories' => $categorieRepository->findAll(),
            'marques' => $marqueRepository->findAll(),
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit')]
    public function produit(int $id, ProduitRepository $produitRepository, DocumentManager $dm): Response
    {
        $produit = $produitRepository->find($id);

        // Récupérer les avis MongoDB de ce produit
        $avis = $dm->getRepository(Avis::class)->findBy(['produitId' => $id]);

        return $this->render('home/produit.html.twig', [
            'produit' => $produit,
            'avis' => $avis,
        ]);
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