<?php

namespace App\Controller;

use App\Repository\AuthorRepository as AuthorRep;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    // afficher liste des auteurs
    #[Route('/author/list', name: 'app_author_list')]
    public function afficher (AuthorRep $authorRepository)   : Response
    {
        $authors = $authorRepository->findAll(); // SELECT * FROM author
        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }
    // ajoute Statique d'un auteur
    #[Route('/author/addStatique', name: 'app_author_add')]
    public function addStatique (AuthorRep $authorRepository)   : Response
    {
        $author = new Author();
        $author->setUsername('test');
        $author->setEmail('test@gmail.com');

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute('app_author_list');
    }
    #[Route('/author/add', name: 'app_author_add2')]
    public function add ( Request $request )
    {
        $author = new Author(); // objet vide
        $form = $this->createForm(AuthorType::class, $author); // objet vide + formulaire
        $form -> add('submit', SubmitType::class, ['label' => 'Ajouter']); // ajout du bouton submit
        $form->handleRequest($request); // traitement de la requete
        if ($form->isSubmitted() && $form->isValid()) // si le formulaire est soumis et valide
        {
            $author = $form->getData(); // recupere les données du formulaire
            $entityManager = $this->getDoctrine()->getManager(); // recupere le manager de doctrine
            $entityManager->persist($author);// prepare la requete
            $entityManager->flush();// execute la requete
            return $this->redirectToRoute('app_author_list'); // redirection
        }
        return $this->render('author/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/author/edit/{id}', name: 'app_author_edit')]
    function edit (AuthorRep $authorRepository , Request $request)
    {
        $author = $authorRepository->find($request->get('id'));
        $form = $this->createForm(AuthorType::class, $author);
        $form -> add('submit', SubmitType::class, ['label' => 'Modifier']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $author = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_author_list');
        }
        return $this->render('author/edit.html.twig', [
            'f' => $form->createView(),
        ]);

    }
    #[Route('/author/delete/{id}', name: 'app_author_delete')]
    function delete (AuthorRep $authorRepository , Request $request)
    {
        $author = $authorRepository->find($request->get('id'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($author);
        $entityManager->flush();
        return $this->redirectToRoute('app_author_list');
    }
  
}
