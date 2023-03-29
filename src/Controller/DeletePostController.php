<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Comments;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class DeletePostController extends AbstractController
{
    #[Route("/posts/remove/{id}", name:"app_delete_post")]
    
    public function deletePost(int $id,Posts $post, Comments $comment, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $postsRepository = $entityManager->getRepository(Posts::class);
        
        if (!$post) {
            throw $this->createNotFoundException(sprintf('The post with id "%s" could not be found', $request->get('id')));
        }

        // Supprimer les commentaires du post
        $comments = $post->getComments();
        foreach ($comments as $comment) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        // Supprimer le post lui-même
        $entityManager = $doctrine->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        // Redirection vers la liste des posts
        return $this->redirectToRoute('app_profile');
    }
}
