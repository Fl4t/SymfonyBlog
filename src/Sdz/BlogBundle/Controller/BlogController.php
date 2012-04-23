<?php
namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Httpfoundation\Response;
use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Entity\Tag;
use Sdz\BlogBundle\Form\ArticleType;
use Sdz\BlogBundle\Form\ArticleHandler;

class BlogController extends Controller {

  public function indexAction() {
    $em = $this->getDoctrine()->getEntityManager();
    $articles = $em->getRepository('SdzBlogBundle:Article')->findAll();
    return $this->render('SdzBlogBundle:Blog:index.html.twig', array(
      'articles' => $articles,
      'index' => true,
    ));
  }

  public function voirAction($id) {
    $em = $this->getDoctrine();
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id);
    return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
      'article' => $article
    ));
  }

  public function ajouterAction() {
    $request = $this->get('request');
    $article = new Article;
    $em = $this->getDoctrine()->getEntityManager();
    $form = $this->createForm(new ArticleType, $article);
    $formHandler = new ArticleHandler($form, $request, $em);
    if($formHandler->process()) {
      $this->get('session')->setFlash('info', 'Article bien enregistré');
      return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
    }
    return $this->render('SdzBlogBundle:Blog:ajouter.html.twig', array(
      'form' => $form->createView(),
      'ajouter' => true,
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
      return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
    }
    return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array(
      'form' => $form->createView(),
      'article' => $article,
    ));
  }

  public function supprimerAction($id) {
    $em = $this->getDoctrine()->getEntityManager();
    $article = $em->getRepository('SdzBlogBundle:Article')->find($id);
    $em->remove($article);
    $em->flush();
    $this->get('session')->setFlash('info', 'Article bien supprimé');
    return $this->redirect($this->generateUrl('sdzblog_index'));
  }

  public function ajouterTagAction() {
    $tag = new Tag;
    $formBuilder = $this->createFormBuilder($tag);
    $formBuilder->add('nom');
    $form = $formBuilder->getForm();
    if ($this->getRequest()->getMethod() == 'POST') {
      $form->bindRequest($this->getRequest());
      if($form->isValid()) {
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($tag);
        $em->flush();
        $this->get('session')->setFlash('info', 'Tag bien ajouté');
        return $this->redirect($this->generateUrl('sdzblog_index'));
      }
    }
    return $this->render('SdzBlogBundle:Blog:tag.html.twig', array(
      'form' => $form->createView(),
      'ajouterTag' => true,
    ));
  }

}
