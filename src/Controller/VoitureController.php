<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Entity\Voiture;
use App\Form\ChauffeurType;
use App\Repository\ChauffeurRepository;
use App\Repository\VoitureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VoitureController extends AbstractController
{
    #[Route('/list', name: 'list_voiture')]
    public function list(VoitureRepository $repo): Response
    {
        return $this->render('voiture/list.html.twig', [
            'voitures' => $repo->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_voiture')]
    public function delete(VoitureRepository $repo, Voiture $v): Response
    {
        $repo->remove($v, true);
        return $this->redirectToRoute('list_voiture');
    }

    #[Route('/louer/{id}', name: 'louer_voiture')]
    public function louer(Request $req, ManagerRegistry $doctrine, Voiture $v): Response
    {
        $chauffeur = new Chauffeur();
        $form = $this->createForm(ChauffeurType::class, $chauffeur);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $v->setChauffeur($chauffeur);
            $em = $doctrine->getManager();
            $em->persist($chauffeur);
            $em->flush();
            return $this->redirectToRoute('list_voiture');
        }
        return $this->renderForm('voiture/addChauffeur.html.twig', [
            'form' => $form,
            'idV' => $v->getId(),
            'marque' => $v->getMarque(),
        ]);
    }

    #[Route('/nombre/{id}', name: 'nombre_voiture')]
    public function nombre(VoitureRepository $repo, Voiture $v): Response
    {
        $nb = $repo->nombreVoiture($v->getMarque());
        return $this->render('voiture/list.html.twig', [
            'nb' => $nb,
            'voitures' => $repo->findAll(),
            'marque' => $v->getMarque()
        ]);
    }
}
