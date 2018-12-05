<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index() /*affiche la liste des produits*/
    {
        $libelle = $this->getDoctrine()->getRepository(Categorie::class)->findAll(); /*recupère les valeurs de la table categorie pour pouvoir les afficher ensuite*/
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll(); /*recupère les valeurs de la table produit*/

        /*renvoi sur la page index en affichant les données de $produits et les données de $libelle*/
        return $this->render('produit/index.html.twig', [
            'produits' => $produits, 'libelle' => $libelle, /*valeur appelée dans l'index.html.twig*/
        ]);
    }



      /**
      * @Route("/produit/ajouter")
      * Method({"GET", "POST"})
      */
      public function ajoutProduit(Request $request) /*permet d'ajouter un nouveau produit*/
      {
          $produit = new Produit();
          $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

          $form = $this->createFormBuilder($produit)
              ->add('idCategorie', EntityType::class, array('class' => Categorie::class, 'choice_label' => 'libelle'))
              ->add('description', TextType::class, array('attr' =>
                  array('class' => 'form-control')))
              ->add('nom', TextType::class, array('attr' =>
                  array('class' => 'form-control')))
              ->add('reference', TextType::class, array('attr' =>
                  array('class' => 'form-control')))
              ->add('EAN', TextType::class, array('attr' =>
                  array('class' => 'form-control')))

              ->add('photo', FileType::class, array('attr' =>
                  array('class' => 'form-control border-0')))


              ->add('sauvegarde', SubmitType::class, array(
                  'label' => 'Créer un produit',
                  'attr' => array('class' => 'btn btn-success mt-5')))
              ->getForm();

              $form->handleRequest($request);

              if($form->isSubmitted() && $form->isValid())
              {
                  $newProduit = $form->getData(); //récupère les info du form

                  $file = $produit->getPhoto();

                  $fileName = substr(base_convert(md5(microtime()), 16, 36), 0, 8).'.'.$file->guessExtension();
                   // Move the file to the directory where brochures are stored
                  try
                  {
                      $file->move(
                          $this->getParameter('images_dir'),
                          $fileName
                      );
                  }
                  catch (FileException $e)
                  {
                      // ... handle exception if something happens during file upload
                  }

                  $newProduit->setPhoto($fileName);

                  $entityManager = $this->getDoctrine()->getManager();
                  $entityManager->persist($newProduit); /*enregistre un nouveau produit*/
                  $entityManager->flush();

                  return $this->redirect('/produit');
              }

              return $this->render('produit/ajouter.html.twig',array(
              'form' => $form->createView()));
      }

    /**
    * @Route("/produit/supprimer/{id}")
    */
    public function supprimerProduit($id) /*permet de supprimer un produit à partir de son id*/
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produits);
        $entityManager->flush();

        return $this->redirect('/');
    }

    /**
    * @Route("/produit/modifier/{id}")
    * Method({"GET", "POST"})
    */
    public function modifierProduit(Request $request, $id) /*permet de modifier un produit à partir de son id*/
    {
        $libelle = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $produits = new Produit();
        $produits = $this->getDoctrine()->getRepository(Produit::class)->find($id);

        $actualFileName =  $produits->getPhoto();

        $produits->setPhoto(new File(($this->getParameter('images_dir').$actualFileName)));


        $form = $this->createFormBuilder($produits)
            ->add('idCategorie', EntityType::class, array('class' => Categorie::class, 'choice_label' => 'libelle'))
            ->add('description', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('nom', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('reference', TextType::class, array('attr' =>
                array('class' => 'form-control')))
            ->add('EAN', TextType::class, array('attr' =>
                array('class' => 'form-control')))

            ->add('photo', FileType::class, array('attr' =>array('class' => 'form-control border-0'), 'required'=>false))

            ->add('sauvegarde', SubmitType::class, array(
                'label' => 'Modifier un produit',
                'attr' => array('class' => 'btn btn-success mt-5 mb-5')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $prodMod = $form->getData();

            if(is_null($prodMod->getPhoto())){
                $prodMod->setPhoto($actualFileName);
            }
            else{

                $file = $prodMod->getPhoto();

                $fileName = substr(base_convert(md5(microtime()), 16, 36), 0, 8).'.'.$file->guessExtension();
                 // Move the file to the directory where brochures are stored
                try
                {
                    $file->move(
                        $this->getParameter('images_dir'),
                        $fileName
                    );
                }
                catch (FileException $e)
                {
                    // ... handle exception if something happens during file upload
                }

                $prodMod->setPhoto($fileName);
            }
            $entityManager->persist($prodMod);
            $entityManager->flush();

            return $this->redirect('/produit');
        }

        return $this->render('produit/modifier.html.twig',array(
            'form' => $form->createView()));
    }


    /**
    * @Route("/produit/{id}")
    */
    public function affichageProduit($id) /*permet d'afficher la fiche technique du produit selectionné*/
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        $categories = $entityManager->getRepository(Categorie::class)->findOneBy(['id' => $produit->getIdCategorie()]);

        return $this->render('produit/afficher.html.twig', array('produit' => $produit, 'categories' => $categories, ));
    }
}
