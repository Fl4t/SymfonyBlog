<?php
namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Form\ArticleType;
use Sdz\BlogBundle\Form\ArticleHandler;

class BlogController extends Controller {

  public function indexAction($page) {
    if( $page < 1 ) {
      throw $this->createNotFoundException('Page inexistante (page = '.$page.')');
    }
    $doctrine = $this->getDoctrine();
    $em = $doctrine->getEntityManager();
    $articles = $em->getRepository('SdzBlogBundle:Article')->findAll();
    return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
      'articles' => $articles
    ));
  }

  public function voirAction($id) {
    $doctrine = $this->getDoctrine();
    $em = $doctrine->getEntityManager();
    $articles = $em->getRepository('SdzBlogBundle:Article');
    $article = $articles->find($id);
    return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
      'article' => $article
    ));
  }

  public function ajouterAction() {
    $article = new Article;
    $form = $this->createForm(new ArticleType, $article);
    $formHandler = new ArticleHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());
    if( $formHandler->process() ) {
      $this->get('session')->setFlash('info', 'Article bien enregistré');
      return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())) );
    }
    return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function modifierAction($id) {
    $em = $this->getDoctrine()->getEntityManager();
    if( ! $article = $em->getRepository('Sdz\BlogBundle\Entity\Article')->find($id) ) {
      throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
    }
    $form = $this->createForm(new ArticleType, $article);
    $formHandler = new ArticleHandler($form, $this->get('request'), $em);
    if( $formHandler->process() ) {
      $this->get('session')->setFlash('info', 'Article bien enregistré');
      return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())) );
    }
    return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
      'form' => $form->createView(),
      'article' => $article,
    ));
  }

  public function supprimerAction($id) {
    $doctrine = $this->getDoctrine();
    $em = $doctrine->getEntityManager();
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id);
    $em->remove($article);
    $em->flush();
    $this->get('session')->setFlash('info', 'Article bien supprimé');
    return $this->redirect( $this->generateUrl('sdzblog_index'));
  }

 /**
  * @Assert\True()
  */
  public function testAction() {
      $article = new Article;
      $article->setDate(new \Datetime());  // Champ « date » O.K.
      $article->setTitre('mazeofijazemfij');           // Champ « titre » incorrect : moins de 10 caractères.
      $article->setContenu('blabla');    // Champ « contenu » incorrect : on ne le définit pas.
      $article->setAuteur('Aamfoijmfze');            // Champ « auteur » incorrect : moins de 2 caractères.

      // On récupère le service validator.
      $validator = $this->get('validator');

      // On déclenche la validation.
      $liste_erreurs = $validator->validate($article);

      // Si le tableau n'est pas vide, on affiche les erreurs.
      if(count($liste_erreurs) > 0)
      {
          return new Response(print_r($liste_erreurs, true));
      }
      else
      {
          return new Response("L'article est valide !");
      }
  }
  public function contactAction()
  {
    return $this->render('SdzBlogBundle:Blog:contact.html.twig');
  }
}
