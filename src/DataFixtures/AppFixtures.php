<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Les catégories ---
        $categoriesData = ['Smartphones', 'Ordinateurs', 'Tablettes', 'Accessoires'];
        $categories = [];
        foreach ($categoriesData as $nom) {
            $categorie = new Categorie();
            $categorie->setNom($nom);
            $manager->persist($categorie);
            $categories[$nom] = $categorie;
        }

        // --- Les marques ---
        $marquesData = ['Apple', 'Samsung'];
        $marques = [];
        foreach ($marquesData as $nom) {
            $marque = new Marque();
            $marque->setNom($nom);
            $manager->persist($marque);
            $marques[$nom] = $marque;
        }

        // --- Les produits ---
        $produitsData = [
            ['nom' => 'iPhone 15', 'prix' => '899.00', 'description' => 'Smartphone dernière génération, écran 6,1 pouces, 128 Go.', 'stock' => 24, 'image' => 'iphone15.jpg', 'categorie' => 'Smartphones', 'marque' => 'Apple'],
            ['nom' => 'MacBook Air', 'prix' => '1199.00', 'description' => 'Ordinateur portable léger et puissant, puce M2, 256 Go.', 'stock' => 12, 'image' => 'macbook.jpg', 'categorie' => 'Ordinateurs', 'marque' => 'Apple'],
            ['nom' => 'iPad Pro', 'prix' => '999.00', 'description' => 'Tablette professionnelle, écran Liquid Retina, 128 Go.', 'stock' => 8, 'image' => 'ipad.jpg', 'categorie' => 'Tablettes', 'marque' => 'Apple'],
            ['nom' => 'Casque audio', 'prix' => '79.00', 'description' => 'Casque sans fil à réduction de bruit, autonomie 30h.', 'stock' => 40, 'image' => 'casque.jpg', 'categorie' => 'Accessoires', 'marque' => 'Samsung'],
            ['nom' => 'AirPods', 'prix' => '199.00', 'description' => 'Écouteurs sans fil, son immersif, boîtier de charge.', 'stock' => 30, 'image' => 'airpods.jpg', 'categorie' => 'Accessoires', 'marque' => 'Apple'],
        ];

        foreach ($produitsData as $data) {
            $produit = new Produit();
            $produit->setNom($data['nom']);
            $produit->setPrix($data['prix']);
            $produit->setDescription($data['description']);
            $produit->setStock($data['stock']);
            $produit->setImage($data['image']);
            $produit->setCategorie($categories[$data['categorie']]);
            $produit->setMarque($marques[$data['marque']]);
            $manager->persist($produit);
        }

        // --- Enregistrer le tout en base ---
        $manager->flush();
    }
}