<?php

namespace App\Controller;

use App\Entity\Categorie;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index()
    {
    	$categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/categorie/ajouter_categorie")
    * Method({"GET", "POST"})
    */
    public function ajoutCategorie(Request $request)
    {
    	$categorie = new Categorie();

    	$form = $this->createFormBuilder($categorie)
    		->add('libelle', TextType::class, array('attr' => 
    			array('class' => 'form-control mt-5 mb-5')))

    		->add('photo', FileType::class, array('attr' =>
                array('class' => 'form-control border-0')))

    		->add('sauvegarde', SubmitType::class, array(
				'label' => 'Créer une catégorie',
				'attr' => array('class' => 'btn btn-success mt-5 mb-5')))
    		->getForm();

    		$form->handleRequest($request); 

    		if($form->isSubmitted() && $form->isValid())
			{
				$newCategorie = $form->getData();

				$file = $categorie->getPhoto();

				$fileName = substr(base_convert(md5(microtime()), 16, 36), 0, 8).'.'.$file->guessExtension();
                try
                {
                    $file->move
                    (
                        $this->getParameter('images_dir'),
                        $fileName
                    );
                }
                catch (FileException $e)
                {
                    // ... handle exception if something happens during file upload
                }

                $newCategorie->setPhoto($fileName);

				$entityManager = $this->getDoctrine()->getManager();
				$entityManager->persist($newCategorie);
				$entityManager->flush();

				return $this->redirect('/categorie');
			}

			return $this->render('categorie/ajouter_categorie.html.twig',array(
			'form' => $form->createView()));
    }

    /**
    * @Route("/categorie/modifier_categorie/{id}")
    * Method({"GET", "POST"})
    */
    public function modifierCategorie(Request $request, $id)
    {
    	$categorie = new Categorie();
    	$categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($id);

    	$actualFileName =  $categorie->getPhoto();
    	$categorie->setPhoto(new File(($this->getParameter('images_dir').$actualFileName)));

    	$form = $this->createFormBuilder($categorie)
    		->add('libelle', TextType::class, array('attr' => 
    			array('class' => 'form-control')))
    		->add('photo', FileType::class, array('attr' =>array('class' => 'form-control border-0'), 'required'=>false))
    		->add('sauvegarde', SubmitType::class, array(
				'label' => 'Modifier une catégorie',
				'attr' => array('class' => 'btn btn-success mt-5 mb-5')))
    		->getForm();

		$form->handleRequest($request);
		
		if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $cateMod = $form->getData();

            if(is_null($cateMod->getPhoto())){
                $cateMod->setPhoto($actualFileName);
            }
            else{

                $file = $cateMod->getPhoto();

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

                $cateMod->setPhoto($fileName);
            }
            $entityManager->persist($cateMod);
            $entityManager->flush();

            return $this->redirect('/categorie');
        }

		return $this->render('categorie/modifier_categorie.html.twig',array(
			'form' => $form->createView()));
    }

    /**
     * @Route("/categorie/supprimer_categorie/{id}")
     */
    public function supprimerCategorie($id)
    {
    	$categories = $this->getDoctrine()->getRepository(Categorie::class)->find($id);

    	$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($categories);
		$entityManager->flush();

		return $this->redirect('/categorie');
    }
}