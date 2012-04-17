<?php
namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Form\ArticleType;
use Sdz\BlogBundle\Form\ArticleHandler;

class BlogController extends Controller {

  public function indexAction() {
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
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id);
    return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
      'article' => $article
    ));
  }

  public function ajouterAction() {
    $request = $this->get('request');
    $em = $this->getDoctrine()->getEntityManager();
    $form = $this->createForm(new ArticleType, new Article);
    $formHandler = new ArticleHandler($form, $request, $em);
    if($formHandler->process()) {
      $this->get('session')->setFlash('info', 'Article bien enregistré');
      return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
    }
    return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  public function modifierAction($id) {
    $request = $this->get('request');
    $em = $this->getDoctrine()->getEntityManager();
    if(!$article = $em->getRepository('Sdz\BlogBundle\Entity\Article')->find($id)) {
      throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
    }
    $form = $this->createForm(new ArticleType, $article);
    $formHandler = new ArticleHandler($form, $request, $em);
    if($formHandler->process()) {
      $this->get('session')->setFlash('info', 'Article bien modifié');
      return $this->redirect( $this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
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

}
