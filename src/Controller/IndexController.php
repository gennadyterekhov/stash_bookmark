<?php
// src/Controller/IndexController.php
namespace App\Controller;

use App\Entity\Bookmark;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $bookmarks = $this->getDoctrine()
            ->getRepository(Bookmark::class)
            ->findAll();

        // if (!$bookmarks) {
        //     throw $this->createNotFoundException('No bookmarks found');
        // }

        return $this->render('index/index.html.twig', ["bookmarks" => $bookmarks]);
    }


    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $name = $request->request->get('name', "default name");
        $url = $request->request->get('url', "https://example.com");

        // if empty, fill with defaults
        $name = $name ? $name: "default name";
        $url = $url ? $url: "https://example.com";

        $entityManager = $this->getDoctrine()->getManager();

        $bookmark = new Bookmark();
        $bookmark->setName($name);
        $bookmark->setUrl($url);

        $entityManager->persist($bookmark);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id)
    {
        $bookmark = $this->getDoctrine()
            ->getRepository(Bookmark::class)
            ->find($id);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($bookmark);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }



}