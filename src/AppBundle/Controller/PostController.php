<?php

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostController extends Controller
{

    /**
     * @param $slug
     * @Route("/post/{slug}",
     *          name="post_details"
     * )
     * @return Response
     */
    public function detailsAction($slug)
    {

        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");
        /** @var $post Post */
        $post = $repository->findOneBySlug($slug);

        if (!$post) {
            throw new NotFoundHttpException("post introuvable");
        }

        return $this->render("post/details.html.twig", [
            "post" => $post,
            "answerList" => $post->getAnswers()
        ]);
    }

    /**
     * @Route("post-par-annee/{year}", name="post_by_year",
     *      requirements={"year":"\d{4}"})
     * @param $year
     * @return Response
     */
    public function postByYearAction($year)
    {
        $postRespository = $this->getDoctrine()
            ->getRepository("AppBundle:Post");

        return $this->render("default/theme.html.twig", [
            "title" => "Liste des posts par année ({$year})",
            "postList" => $postRespository->getPostsByYear($year)
        ]);
    }

    /**
     * @Route("/post/modif/{id}", name="post_edit")
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function editAction(Request $request, Post $post)
    {

        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $userId = isset($user) ? $user->getId() : null;
        if (!in_array("ROLE_AUTHOR", $roles) || $userId != $post->getAuthor()->getId()) {
            throw new AccessDeniedException("Vous n'avez pas les droits pour modifier ce post");
        }

        //Création du formulaire
        $form = $this->createForm(PostType::class, $post);

        //Hydratation de l'entité avec la requete
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute("theme_details", ["id" => $post->getTheme()->getId()]);
        }

        return $this->render("post/edit.html.twig", ["postForm"=>$form->createView()]);
    }
}