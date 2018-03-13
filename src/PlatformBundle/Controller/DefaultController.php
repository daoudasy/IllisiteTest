<?php

namespace PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PlatformBundle\Entity\Personne;
use PlatformBundle\Form\PersonneType;
use PlatformBundle\Form\PersonneMajType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /*
		Affichage de la liste des personnes et ajout
    */
    public function indexAction(Request $request, $page){
    	$em = $this->getDoctrine()->getManager();
    	$max = 15;
    	$persons = $em->getRepository('PlatformBundle:Personne')->findAllAndPagine($page, $max);
    	$pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($persons) / $max),
            'nomRoute' => 'platform_homepage',
            'paramsRoute' => array()
        );

        $person = new Personne();
    	$form = $this->get('form.factory')->create(PersonneType::class, $person);
    	if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
            	$em->persist($person);
            	$em->flush();
            	return $this->redirectToRoute('platform_homepage');
            }
        }

        return $this->render('@Platform/Default/index.html.twig',[
        	'persons' => $persons,
        	'pagination' => $pagination,
        	'form' => $form->createView(),
        	]);
    }

    /*
		Suppression d'une personne
    */
    public function deleteAction($id, $page){
    	$em = $this->getDoctrine()->getManager();
    	$person = $em->getRepository('PlatformBundle:Personne')->find($id);
    	if(!empty($person)){
    		$em->remove($person);
    		$em->flush();
    	}
    	return $this->redirectToRoute('platform_homepage', array('page'=>$page));
    }

    /*
		Modification d'une personne
    */
    public function updateAction(Request $request, $id, $page){
    	$em = $this->getDoctrine()->getManager();
    	$person = $em->getRepository('PlatformBundle:Personne')->find($id);
    	if(empty($person))
    		return $this->redirectToRoute('platform_homepage');
    	$form = $this->get('form.factory')->create(PersonneMajType::class, $person);
    	if($request->isMethod('POST')){
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
            	$em->merge($person);
            	$em->flush();
            	return $this->redirectToRoute('platform_homepage', ['page'=>$page]);
            }
        }
    	return $this->render('@Platform/Default/update.html.twig',['form'=>$form->createView(),'page'=>$page]);
    }
}
