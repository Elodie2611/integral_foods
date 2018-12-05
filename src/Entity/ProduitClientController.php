<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Entity\Prix;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

class ProduitClientController extends AbstractController
{
    /**
     * @Route("/randomKey", name="randomKey")
     */

    public function randomKey($length) {
        $key ='';
        $pool = array_merge(range(0,9), range('a', 'z'), range('A', 'Z'));

        for($i=0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }


    /**
     * @Route("/", name="produit_client")
     */
    public function index() /*page d'accueil, menu avec les différentes catégories*/
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $rand = $this->randomKey(8) ;
        return $this->render('produit_client/index.html.twig', [
            'categories' => $categories,'email' => $rand
        ]);
    }

    /**
     * @Route("/produit/client/{id}")
     */
    public function listeProduit($id) /*affiche la liste des produits*/
    {

        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $catrepo = $entityManager->getRepository(Categorie::class)->find($id);
        $produits = $entityManager->getRepository(Produit::class)->findBy(['idCategorie' => $catrepo->getId()]);
        
        return $this->render('produit_client/liste.produit.html.twig', [
         'categories' => $catrepo, 'produits' => $produits, 'email' => $this->randomKey(8)   ]);
    }

    /**
     * @Route("/produit/client/fiche_produit/{id}")
     */
    public function afficheProduit($id)
    {         
        $user = $this->get('security.token_storage')->getToken()->getUser()->getId();        

        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $categories = $entityManager->getRepository(Categorie::class)->findOneBy(['id' => $produit->getIdCategorie()]);
        $prix = $this->getDoctrine()->getRepository(Prix::class)->findBy(['id_produit' => $produit->getId()]);
        
        return $this->render('produit_client/fiche.produit.html.twig', array('produit' => $produit, 'categories' => $categories, 'email' => $user, "prix" => $prix ));
    }
}


