<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\Utilisateur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

use Sensio\Bundle\FrameworkExtreBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commandes", name="commandes")
     */
    public function index()
    {

    $entityManager = $this->getDoctrine()->getManager();
    $commandes = $entityManager->getRepository(Commande::class)->findAll();
    $user = $entityManager->getRepository(Utilisateur::class)->findAll();
    $produit = $entityManager->getRepository(Produit::class)->findAll();

    return $this->render('commande/index.html.twig', array("commandes" => $commandes, "user" => $user, "produit" => $produit));

    }


    /**
     * @Route("commandes/delete/{id}")
     */
    public function delete($id){
    $commandes = $this->getDoctrine()->getRepository(Commande::class)->find($id);
    $entityManager = $this->getDoctrine()->getManager();
   $commandes->setCompte(true);
    $entityManager->flush();
    return $this->redirect('/commandes');
    }


}
