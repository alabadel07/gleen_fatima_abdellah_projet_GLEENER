<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class NewPostController extends AbstractController
{
    #[Route('/new/post', name: 'app_new_post')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Posts();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter le créateur du post
            $post->setCreator($this->getUser());

            // Enregistrer le post dans la base de données
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Le post a été créé avec succès !');
            return $this->redirectToRoute('app_posts');
        }

        return $this->render('post/new.html.twig', [
            'user' => $this->getUser(),
                        'form' => $form->createView(),
        ]);
    }
}
