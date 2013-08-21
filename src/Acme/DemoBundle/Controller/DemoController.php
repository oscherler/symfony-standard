<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Acme\DemoBundle\Form\ContactType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DemoController extends Controller
{
    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/objectchoicelist", name="_demo_objectchoicelist")
     * @Template()
     */
    public function objectChoiceListAction()
    {
    	$foo = new \StdClass();
    	$foo->id = 11;
    	$foo->name = 'Foo';

    	$bar = new \StdClass();
    	$bar->id = 12;
    	$bar->name = 'Bar';

    	$baz = new \StdClass();
    	$baz->id = 13;
    	$baz->name = 'Baz';
    	
    	$another_bar = new \StdClass();
    	$another_bar->id = 12;
    	$another_bar->name = 'Bar';
    	
    	$choices = array( $foo, $bar, $baz );

		$choice_list = new ObjectChoiceList(
			$choices,
			'name',   # label path
			array(),  # preferred choices
			null,     # group path
			'id'      # value path
		);
    	
    	$data = array(
    		'same_instance' => $bar,
    		'same_instance_expanded' => $bar,
    		'same_instance_multiple' => array( $bar ),
    		'same_instance_multiple_expanded' => array( $bar ),
    		'other_instance' => $another_bar,
    		'other_instance_expanded' => $another_bar,
    		'other_instance_multiple' => array( $another_bar ),
    		'other_instance_multiple_expanded' => array( $another_bar ),
    	);
    	
    	$form = $this->createFormBuilder( $data )
    		->add( 'same_instance', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---'
    		) )
    		->add( 'same_instance_expanded', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---',
    			'expanded' => true
    		) )
    		->add( 'same_instance_multiple', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---',
    			'multiple' => true
    		) )
    		->add( 'same_instance_multiple_expanded', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---',
    			'multiple' => true,
    			'expanded' => true
    		) )
    		->add( 'other_instance', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---'
    		) )
    		->add( 'other_instance_expanded', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---',
    			'expanded' => true
    		) )
    		->add( 'other_instance_multiple', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---',
    			'multiple' => true
    		) )
    		->add( 'other_instance_multiple_expanded', 'choice', array(
    			'choice_list' => $choice_list,
    			'empty_value' => '---',
    			'multiple' => true,
    			'expanded' => true
    		) )
    		->getForm();

        return array('form' => $form->createView());
    }

    /**
     * @Route("/hello/{name}", name="_demo_hello")
     * @Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction()
    {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->getFlashBag()->set('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_demo'));
            }
        }

        return array('form' => $form->createView());
    }
}
