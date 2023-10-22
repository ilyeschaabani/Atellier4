<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\AuthorType;
use App\Form\BookType;
use App\Repository\BookRepository as BookRep;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/book/list', name: 'app_book_list')]
    public function list( ): Response
    {
    // recuperé les livre publié
        $publishedbooks = $this->getDoctrine()->getRepository(Book::class)->findBy(['published' => true]);
        // recuperé les livre non publié
        $unpublishedbooks = $this->getDoctrine()->getRepository(Book::class)->findBy(['published' => false]);
        //compter les livre publié et non publié
        $nbPublishedBooks = count($publishedbooks);
        $nbUnpublishedBooks = count($unpublishedbooks);
        $repository = $this->getDoctrine()->getRepository(Book::class);
        if ($nbPublishedBooks > 0)
        {
            return $this->render('book/list.html.twig', [
                'publishedbooks' => $publishedbooks, 'nbPublishedBooks' => $nbPublishedBooks,'unpublishedbooks' => $unpublishedbooks, 'nbUnpublishedBooks' => $nbUnpublishedBooks
            ]);
        }else{
            //afficher message d'erreur
            return $this->render('book/nobook_found.html.twig');
        }

    }
    #[Route('/book/add', name: 'app_author_add2')]
    public function add ( Request $request )
    {
        $Book = new Book(); // objet vide
        $form = $this->createForm(BookType::class, $Book); // objet vide + formulaire
        $form -> add('submit', SubmitType::class, ['label' => 'Ajouter']); // ajout du bouton submit
        $form->handleRequest($request); // traitement de la requete
        if ($form->isSubmitted() && $form->isValid()) // si le formulaire est soumis et valide
        {
            //INITIALISATION DE LATRIBUT "PUBLISHED" A TRUE
            $Book->setPublished(true);
            //incrementation de l'attribut ""nbBooks" de l'objet Author
            $author = $Book->getAuthor();
            $author->setNbBooks($author->getNbBooks()+1);

            $Book = $form->getData(); // recupere les données du formulaire
            $entityManager = $this->getDoctrine()->getManager(); // recupere le manager de doctrine
            $entityManager->persist($Book);// prepare la requete
            $entityManager->flush();// execute la requete
            return $this->redirectToRoute('app_book_list'); // redirection
        }
        return $this->render('book/add.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/book/edit/{ref}', name: 'app_book_edit')]
    function edit (BookRep $bookRep , Request $request)
    {
        $book = $bookRep->find($request->get('ref'));
        $form = $this->createForm(BookType::class, $book);
        $form -> add('submit', SubmitType::class, ['label' => 'Modifier']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $book = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('app_book_list');
        }
        return $this->render('book/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/book/delete/{ref}', name: 'app_book_delete')]
    function delete (BookRep $bookRep , Request $request)
    {
        $book = $bookRep->find($request->get('ref'));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirectToRoute('app_book_list');
    }
    #[Route('/book/show/{ref}', name: 'app_book_show')]
    public function showbook($ref, BookRep $bookRep)
    {
        $book = $bookRep->find($ref);
        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for ref '.$ref
            );
        }
        return $this->render('book/showbook.html.twig', [
            'book' => $book,
        ]);
    }

}
