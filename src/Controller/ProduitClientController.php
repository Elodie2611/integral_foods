<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use App\Entity\Prix;
use App\Entity\Commande;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

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
                       var_dump( $this->get('session')->get('panier'));
        return $this->render('produit_client/fiche.produit.html.twig', array('produit' => $produit, 'categories' => $categories, 'email' => $user, "prix" => $prix ));
    }

    /**
     * @Route("/produit/client/fiche_produit/panier/{id},{prix},{nom}")
     */
    public function ajouterPanier($id, $prix, $nom) {
         $entityManager = $this->getDoctrine()->getManager();
         $user = $this->get('security.token_storage')->getToken()->getUser()->getId();
        if (null !==$this->get('session')->get('panier')){
            $panier = $this->get('session')->get('panier');
        }
        else
        {
            $panier= [];
        }


        $add = array('id' => $id, 'prix'=> $prix, 'nom' => $nom, 'quantite'=>1);
        array_push($panier, $add);

        $this->get('session')->set('panier', $panier);

        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $categories = $entityManager->getRepository(Categorie::class)->findOneBy(['id' => $produit->getIdCategorie()]);
        $prix = $this->getDoctrine()->getRepository(Prix::class)->findBy(['id_produit' => $produit->getId()]);
                var_dump( $this->get('session')->get('panier'));
        return $this->render('produit_client/fiche.produit.html.twig', array('produit' => $produit, 'categories' => $categories, 'email' => $user, "prix" => $prix ));

    }

    /**
     * @Route("/panier")
     */

        public function affichPanier(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser()->getId();
        $lePanier = $this->get('session')->get('panier');

        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($lePanier)
        // foreach ($lePanier as $key => $value) {
        //   $form->add($key, TextType::class);
        // }

        ->add('id', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('quantite', NumberType::class, array('attr' => array('class' => 'form-control')))
        ->add('prix', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('send', SubmitType::class, array('label' => 'Valider', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
        }

        return $this->render('produit_client/panier.html.twig', array('panier' => $lePanier,'email' => $user, 'form'=> $form->createView()));

    }

    /**
     * @Route("/AjoutCommande")
     */
     public function AjoutCommande(Request $request){

           $user = $this->get('security.token_storage')->getToken()->getUser()->getId();
           $lePanier = $this->get('session')->get('panier');
           $entityManager = $this->getDoctrine()->getManager();
      if ($request->getMethod() == Request::METHOD_POST){
          foreach ($lePanier as $key => $value) {

              $produit = $entityManager->getRepository(Produit::class)->findOneBy(['id' => $value['id']]);
              $prix = $this->getDoctrine()->getRepository(Prix::class)->findOneBy(['id_utilisateur' => $user]);
              $commande = new Commande();
              $ligne = $request->request->get($value['id']);

              $commande->setIdUtilisateur($user);
              $commande->setIdProduit($value['id']);
              $commande->setQuantite($ligne);
        
              $commande->setPrix($prix->getPrix());
              $date = new \DateTime("now");
              $commande->setDateCommande($date);
              $entityManager->persist($commande);

              $entityManager->flush();

          }
          return $this->redirect('/');
      }

     }

}
