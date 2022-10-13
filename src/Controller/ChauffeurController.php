<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Form\ChauffeurType;
use App\Repository\ChauffeurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChauffeurController extends AbstractController
{
    #[Route('/listC', name: 'list_chauffeur')]
    public function list(ChauffeurRepository $repo): Response
    {
        return $this->render('chauffeur/list.html.twig', [
            'chauffeurs' => $repo->findAll(),
        ]);
    }

    #[Route('/update/{id}', name: 'update_chauffeur')]
    public function update(Request $req, ChauffeurRepository $repo, Chauffeur $c): Response
    {
        $form = $this->createForm(ChauffeurType::class, $c);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($c, true);
            return $this->redirectToRoute('list_chauffeur');
        }
        return $this->renderForm('chauffeur/form.html.twig', [
            'form' => $form,
        ]);
    }
}
