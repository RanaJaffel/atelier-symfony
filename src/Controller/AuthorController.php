<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository ;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Author;

class AuthorController extends AbstractController
{
    #[Route('/author/{name}', name: 'show_author')]
    public function showAuthor(string $name): Response
    {
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    #[Route('/authors', name: 'list_authors')]
    public function listAuthors(): Response
    {
        $authors = [
            ['id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            ['id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            ['id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        return $this->render('author/list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/details/{id}', name: 'author_details')]
    public function authorDetails(int $id): Response
    {
        $authors = [
            1 => ['picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100],
            2 => ['picture' => '/images/william-shakespeare.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200],
            3 => ['picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300],
        ];

        $author = $authors[$id] ?? null;

        if (!$author) {
            throw $this->createNotFoundException("Auteur introuvable !");
        }

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
        ]);
    }
    //#[Route('/getAll', name: 'app_get')]
    //public function getAllAuthor (AuthorRepository $authrepo){
        //$authors = $authrepo->findAll();
        //render ('author/list.html.twig');
        //'authors' ->$authors;
    //}
    #[Route('/getAll', name: 'app_get')]
    public function getAllAuthor(AuthorRepository $authrepo): Response{
        $authors = $authrepo->findAll();
        return $this->render('author/tableauauthors.html.twig', [
        'authors' => $authors
    ]);
}
    #[Route('/addAuth', name: 'app_add')]
    public function AddAuthor(ManagerRegistry $em): Response{
        $auth1 =new Author();
        $auth1->setUsername('Author1');
        $auth1->setEmail('author1@esprit.tn');

        $auth2 =new Author();
        $auth2->setUsername('Author2');
        $auth2->setEmail('author2@esprit.tn');

        $em->getManager()->persist($auth1);
        $em->getManager()->persist($auth2);
        $em->getManager()->flush();

        return new Response('Author Added');
}

    #[Route('/author/delete/{id}', name: 'author_delete')]
    public function deleteAuthor(int $id, ManagerRegistry $doctrine, AuthorRepository $authRepo): Response
{
    $author = $authRepo->find($id);
    $em = $doctrine->getManager();
    $em->remove($author);
    $em->flush();

    return new Response("Auteur avec l'id $id supprimé !");
}

#[Route('/updateAuth/{id}', name: 'app_update')]
public function updateAuthor(ManagerRegistry $doctrine, int $id): Response
{
    $em = $doctrine->getManager();
    $author = $em->getRepository(Author::class)->find($id);

    if (!$author) {
        return new Response('Auteur introuvable avec id ' . $id);
    }

    // Modification des champs (exemple simple)
    $author->setUsername('UsernameUpdated');
    $author->setEmail('updatedemail@esprit.tn');

    $em->flush(); // applique les modifications

    return new Response('Auteur mis à jour avec succès (id: ' . $id . ')');
}
}

