<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\User;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtreBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;



class UtilisateurController  extends AbstractController 
{
	
	 /**
     * @Route("utilisateur/newUser")
     * Method({"GET", "POST"})
     */
public function newUser(Request $request, UserPasswordEncoderInterface $passwordEncoder){

	$user = new User();
	$utilisateur = new Utilisateur();


	$form = $this->createFormBuilder($user)
	->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
	->add('password', PasswordType::class, array('attr' => array('class' => 'form-control')))
	->add('sauvegarde', SubmitType::class, array('label' => 'Créer utilisateur', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
	->getform();
		
	$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){	
			$password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
			$user = $form->getData();
			$email = $user->getEmail();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($user);
			$entityManager->flush();
			
			$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email' => $email));
	
			$id = $user->getId();
			$this->get('session')->set('userId', $id);

			
			return $this->redirect('/utilisateur/nextStep');
		}

	return $this->render('utilisateur/newUser.html.twig', array(
	'form'=> $form->createView()));

}


 /**
     * @Route("utilisateur/modifyAccount")
     * Method({"GET", "POST"})
     */
public function modifyAccount(Request $request, UserPasswordEncoderInterface $passwordEncoder){

	$user = new User();
	$id_user = $this->get('security.token_storage')->getToken()->getUser()->getId();
			
	$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('id' => $id_user));

	$form = $this->createFormBuilder($user)
	->add('email', EmailType::class, array('attr' => array('class' => 'form-control')))
	->add('password', PasswordType::class, array('attr' => array('class' => 'form-control')))
	->add('sauvegarde', SubmitType::class, array('label' => 'valider les modifications', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
	->getform();
		
	$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){	

		

			$password = $passwordEncoder->encodePassword($user, $form->get('password')->getData());
			$user->setPassword($password);
			$user->setEmail($form->get('email')->getData());
			$user = $form->getData();
			$entityManager = $this->getDoctrine()->getManager();

			$entityManager->flush();
			return $this->redirect('/');
		}

	return $this->render('utilisateur/editAccount.html.twig', array(
	'form'=> $form->createView()));

}


/**
     * @Route("/randomKey", name="randomKey")
     */

public function randomKey($length) {
	$key ='';
    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

    for($i=0; $i < $length; $i++) {
        $key .= $pool[mt_rand(0, count($pool) - 1)];
    }
    return $key;
}


 /**
     * @Route("utilisateur/forgetPassword")
     * Method({"GET", "POST"})
     */
public function forgetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder) {

	$user = new User();
	$form = $this->createFormBuilder($user)
	->add('email', TextType::class, array('attr' => array('class' => 'form-control')))
	->add('sauvegarde', SubmitType::class, array('label' => 'Réinitialiser mot de passe', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
	->getform();
		
	$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){	
			$user = $form->getData();
			$email = $form->get('email')->getData();
			
			$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('email' => $email));
			
			$random = $this->randomKey(15);
			// Pour crypter la nouvelle clé, mettre $random à la place du $user->getPassword()
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
  			// et mettre le $password ici
            $user->setPassword($random);
			
            //Mettre une fonction d'envois de mail pour envoyer la clé générée à l'utilisateur

            //Fin fonction envoie mail

			$entityManager = $this->getDoctrine()->getManager();
		
			$entityManager->flush();
			//return $this->redirect('/login');
		}

	return $this->render('utilisateur/forgetPassword.html.twig', array(
	'form'=> $form->createView()));
}




    /**
     * @Route("/utilisateur", name="utilisateur")
     */
    public function index()
    {
	$utilisateurs = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
    return $this->render('utilisateur/index.html.twig', array("utilisateurs" => $utilisateurs));
		
    }

  

    /**
     * @Route("utilisateur/delete/{id}", name="delete")
     */
    public function delete($id){
	$utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
 	$entityManager = $this->getDoctrine()->getManager();
	$entityManager->remove($utilisateur);
	$entityManager->flush();

	return $this->redirect('/utilisateur');
}



 /**
     * @Route("utilisateur/nextStep")
     * Method({"GET", "POST"})
     */
public function nouveau(Request $request){
	
	$utilisateur = new Utilisateur();
	$id = $this->get('session')->get('userId');
	$form = $this->createFormBuilder($utilisateur)
		->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('user_id', HiddenType::class , array('data' => $id))
		->add('prenom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('civilite', ChoiceType::class, array('choices'  => array(
        'Monsieur' => "Monsieur",
        'Madame' => 'Madame')))
		->add('entreprise', TextType::class, array('attr' => array('class' => 'form-control')))		

		->add('type_etablissement', ChoiceType::class, array('choices'  => array(
        'distributeur' => "distributeur",
        'restaurant indépendant' => 'restaurant indépendant',
        'chaine de restaurant ou franchise' => 'chaine de restaurant ou franchise',
   		'épicerie fine' => "épicerie fine",
		'collectivité' => "collectivité",
		'traiteur' => "traiteur",
		'industrie agroalimentaire' => "industrie agroalimentaire",
		'autre' => "autre")))

		->add('etablissement_autre', TextType::class, array('attr' => array('class' => 'form-control')))

		
		->add('tel', TextType::class, array('attr' => array('class' => 'form-control')))
		
		->add('siret', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('num_tva', TextType::class, array('attr' => array('class' => 'form-control')))
	
		->add('description', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('date_inscription', HiddenType::class, array('attr' => array('class' => 'form-control')))
		->add('sauvegarde', SubmitType::class, array('label' => 'Créer utilisateur', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
		->getform();

		$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){
			$newUtilisateur = $form->getData();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($newUtilisateur);
            $date = new \DateTime("now");  
			$utilisateur->setDateInscription($date);
			$utilisateur->setStatusInscription(0); 
			$entityManager->flush();

			return $this->redirect('/');
		}

		return $this->render('utilisateur/nextStep.html.twig', array(
			'form'=> $form->createView()));
}



 /**
     * @Route("utilisateur/modify/{id}")
     * Method({"GET", "POST"})
     */
public function modify(Request $request, $id){
	$utilisateur = new Utilisateur();
	$utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);

	$form = $this->createFormBuilder($utilisateur)
		->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('user_id', HiddenType::class, array('attr' => array('class' => 'form-control')))
		->add('prenom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('civilite', ChoiceType::class, array('choices'  => array(
        'Monsieur' => "Monsieur",
        'Madame' => 'Madame')))
		->add('entreprise', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('type_etablissement', ChoiceType::class, array('choices'  => array(
        'distributeur' => "distributeur",
        'restaurant indépendant' => 'restaurant indépendant',
        'chaine de restaurant ou franchise' => 'chaine de restaurant ou franchise',
   		'épicerie fine' => "épicerie fine",
		'collectivité' => "collectivité",
		'traiteur' => "traiteur",
		'industrie agroalimentaire' => "industrie agroalimentaire",
		'autre' => "autre")))
		->add('etablissement_autre', TextType::class, array('attr' => array('class' => 'form-control',  'required' => false)))
		->add('status_inscription', CheckboxType::class, array(
    		'label'    => 'statut inscription',
    		'required' => false,
			))
		->add('tel', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('siret', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('num_tva', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('description', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('date_inscription', DateType::class, array('attr' => array('class' => 'form-control border-0'), 'disabled'=>true))
		->add('sauvegarde', SubmitType::class, array('label' => 'modifier utilisateur', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
		->getform();

		$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){
			
			$entityManager = $this->getDoctrine()->getManager();
			
			$entityManager->flush();
			return $this->redirect('/utilisateur');
		}

		return $this->render('utilisateur/edit.html.twig', array(
			'form'=> $form->createView()));
}


 /**
     * @Route("utilisateur/modifyUser")
     * Method({"GET", "POST"})
     */
public function modifyUser(Request $request){
	$utilisateur = new Utilisateur();
	$id = $this->get('security.token_storage')->getToken()->getUser()->getId();
	$utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(array('user_Id' => $id));

	$form = $this->createFormBuilder($utilisateur)
		->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('user_id', HiddenType::class, array('attr' => array('class' => 'form-control')))
		->add('prenom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('civilite', ChoiceType::class, array('choices'  => array(
        'Monsieur' => "Monsieur",
        'Madame' => 'Madame')))
		->add('entreprise', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('type_etablissement', ChoiceType::class, array('choices'  => array(
        'distributeur' => "distributeur",
        'restaurant indépendant' => 'restaurant indépendant',
        'chaine de restaurant ou franchise' => 'chaine de restaurant ou franchise',
   		'épicerie fine' => "épicerie fine",
		'collectivité' => "collectivité",
		'traiteur' => "traiteur",
		'industrie agroalimentaire' => "industrie agroalimentaire",
		'autre' => "autre")))
		->add('etablissement_autre', TextType::class, array('attr' => array('class' => 'form-control'),  'required' => false,  'empty_data' => 'non indiqué',))
		->add('status_inscription', CheckboxType::class, array(
    		'label'    => 'statut inscription',
    		'required' => false,
    		'disabled' => true
			))
		->add('tel', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('siret', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('num_tva', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('description', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('date_inscription', DateType::class, array('attr' => array('class' => 'form-control', 'disabled' => true), 'disabled' => true))
		->add('sauvegarde', SubmitType::class, array('label' => 'modifier utilisateur', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
		->getform();

		$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){
			
			$entityManager = $this->getDoctrine()->getManager();
			$rand = $this->randomKey(6);
			$entityManager->flush();
			return $this->redirect('/utilisateur/profil/'.$rand);
		}

		return $this->render('utilisateur/edit.html.twig', array(
			'form'=> $form->createView()));
}





 /**
     * @Route("utilisateur/{id}")
     */

public function affichUser($id){
	$utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->find($id);
    return $this->render('utilisateur/single.html.twig', array("utilisateur" => $utilisateur) );
}

 /**
     * @Route("utilisateur/profil/{id}")
     */

public function affichProfile($id, Request $request){
	try{
   
		$id_user = $this->get('security.token_storage')->getToken()->getUser()->getId();
		$user = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(array('user_Id' => $id_user));
    return $this->render('utilisateur/profil.html.twig', array("utilisateur" => $user) );
}
catch(\Exception $e){

	$utilisateur = new Utilisateur();
	$id = $this->get('security.token_storage')->getToken()->getUser()->getId();
	$form = $this->createFormBuilder($utilisateur)
		->add('nom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('user_id', HiddenType::class , array('data' => $id))
		->add('prenom', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('civilite', ChoiceType::class, array('choices'  => array(
        'Monsieur' => "Monsieur",
        'Madame' => 'Madame')))
		->add('entreprise', TextType::class, array('attr' => array('class' => 'form-control')))		

		->add('type_etablissement', ChoiceType::class, array('choices'  => array(
        'distributeur' => "distributeur",
        'restaurant indépendant' => 'restaurant indépendant',
        'chaine de restaurant ou franchise' => 'chaine de restaurant ou franchise',
   		'épicerie fine' => "épicerie fine",
		'collectivité' => "collectivité",
		'traiteur' => "traiteur",
		'industrie agroalimentaire' => "industrie agroalimentaire",
		'autre' => "autre")))

		->add('etablissement_autre', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('tel', TextType::class, array('attr' => array('class' => 'form-control')))
		
		->add('siret', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('num_tva', TextType::class, array('attr' => array('class' => 'form-control')))
	
		->add('description', TextType::class, array('attr' => array('class' => 'form-control')))
		->add('date_inscription', HiddenType::class, array('attr' => array('class' => 'form-control')))
		->add('sauvegarde', SubmitType::class, array('label' => 'inscription', 'attr' => array('class' => 'btn btn-success mb-5 mt-5')))
		->getform();

		$form->handleRequest($request);
		if($form->isSubmitted()&&$form->isValid()){
			$newUtilisateur = $form->getData();
			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($newUtilisateur);
            $date = new \DateTime("now");  
			$utilisateur->setDateInscription($date);
			$utilisateur->setStatusInscription(0); 
			$entityManager->flush();

			return $this->redirect('/');
		}

		return $this->render('utilisateur/nextStep.html.twig', array(
			'form'=> $form->createView()));
}

}


}
