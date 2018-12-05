<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Entity\Prix;

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

class PrixController extends AbstractController
{
    /**
     * @Route("prix/nouveau")
     */
    public function nouveau(Request $request)
    {
        $prix = new Prix();
        $user = new Utilisateur();

        $form = $this->createFormBuilder($prix)


       ->add('id_utilisateur', EntityType::class, array('class' => Utilisateur::class, 'choice_label' => 'entreprise', 'data' => 'user_Id'))
        


        ->add('id_produit', EntityType::class, array('class' => Produit::class, 'choice_label' => 'nom',   'data' => 'id_produit'))
        
        ->add('prix', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('sauvegarde', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success mt-5 mb-5')))
        ->getform();

        $form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){

			$newPrix = $form->getData();
            $idUser = $form->get('id_utilisateur')->getData()->getUserId();
            $idProd = $form->get('id_produit')->getData()->getId();
            $prix = $form->get('prix')->getData();
            $entityManager = $this->getDoctrine()->getManager();
          
            $entityManager->persist($newPrix);
		   
		
			
			$entityManager->flush();
            var_dump($idUser);
			return $this->redirect('/prix');
		}

		return $this->render('prix/nouveau.html.twig', array(
			'form'=> $form->createView()));

    }


     /**
     * @Route("/prix", name="prix")
     */

    public function affichPrix()
    {

        /*$prix = $this->getDoctrine()->getRepository(Prix::class)->findAll();*/

        $entityManager = $this->getDoctrine()->getManager();
        $prix = $entityManager->getRepository(Prix::class)->findAll();
        $user = $entityManager->getRepository(Utilisateur::class)->findAll();
        $produit = $entityManager->getRepository(Produit::class)->findAll();

        return $this->render('prix/index.html.twig', array("prix" => $prix, "user" => $user, "produit" => $produit) );


      
    }


     /**
     * @Route("prix/delete/{id}")
     */
    public function delete($id){
	$prix = $this->getDoctrine()->getRepository(Prix::class)->find($id);
 	$entityManager = $this->getDoctrine()->getManager();
	$entityManager->remove($prix);
	$entityManager->flush();

	return $this->redirect('/prix');
	}


	/**
     * @Route("prix/update/{id}")
     * Method({"GET", "POST"})
     */
	public function update(Request $request, $id){
	$prix = new Prix();
	$prix = $this->getDoctrine()->getRepository(Prix::class)->find($id);

	$form = $this->createFormBuilder($prix)
		->add('id_utilisateur', EntityType::class, array('class' => Utilisateur::class, 'choice_label' => 'nom'))

        ->add('id_produit', EntityType::class, array('class' => Produit::class, 'choice_label' => 'nom'))
        ->add('prix', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('sauvegarde', SubmitType::class, array('label' => 'Modifier le prix', 'attr' => array('class' => 'btn btn-success mb-5')))
		->getform();

		$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){
			
			$entityManager = $this->getDoctrine()->getManager();
			
			$entityManager->flush();
			return $this->redirect('/prix');
		}

		return $this->render('prix/edit.html.twig', array(
			'form'=> $form->createView()));
}


    
}
