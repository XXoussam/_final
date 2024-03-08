<?php

namespace App\Controller;

use App\Entity\FinancialHubInvest;
use App\Form\FinancialHubInvestType;
use App\Repository\FinancialHubInvestRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ResponsibleInvestmentHomeController extends AbstractController
{
    #[Route('/responsible/investment/home', name: 'app_responsible_investment_home')]
    public function index(): Response
    {
        return $this->render('responsible_investment_home/index.html.twig', [
            'controller_name' => 'ResponsibleInvestmentHomeController',
        ]);
    }

    #[Route('/staff',name:'staff_app')]
    public function indexastaff():Response
    {
        return $this->render('staffdash/staff/staff.html.twig');
    }


    #[Route('/staffmaninv',name:'staffmaninv_app')]
    public function indestaffmaninv(FinancialHubInvestRepository $repo):Response
    {
        $list=$repo->findAll();
        dump($list);
        return $this->render('staffdash/investManagment/maninv.html.twig',[
            'list'=>$list
        ]);
    }

    #[Route('/invdetal',name:'invdetal_app')]
    public function invdetal():Response
    {
        return $this->render('staffdash/investManagment/investdetail.html.twig');
    }

    #[Route('/invedit/{id}',name:'invedit_app')]
    public function editInvest(Request $req,ManagerRegistry $manager,$id,FinancialHubInvestRepository $repo,SluggerInterface $slugger):Response
    {
        $em=$manager->getManager();
        $invest=$repo->find($id);

        $form=$this->createForm(FinancialHubInvestType::class, $invest);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the  file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('service_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $invest->setImage($newFilename);
            }

            $em->persist($invest);
            $em->flush();
            return $this->redirectToRoute('staffmaninv_app');
        }
        return $this->renderForm('staffdash/investManagment/invedit.html.twig',[
            'f' =>$form
        ]);
    }

    #[Route('/invdelete/{id}',name:'invdelete_app')]
    public function invdelete(ManagerRegistry $manager,$id,FinancialHubInvestRepository $repo):Response
    {
        $em=$manager->getManager();
        $invest=$repo->find($id);
        $em->remove($invest);
        $em->flush();
        return $this->redirectToRoute('staffmaninv_app');
    }

    #[Route('/invadd',name:'invadd_app')]
    public function addInvest(Request $req,ManagerRegistry $manager,SluggerInterface $slugger):Response
    {
        $em=$manager->getManager();
        $invest=new FinancialHubInvest();
        $form=$this->createForm(FinancialHubInvestType::class, $invest);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){

            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('service_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $invest->setImage($newFilename);
            }

            $em->persist($invest);
            $em->flush();
            return $this->redirectToRoute('staffmaninv_app');
        }
        return $this->renderForm('staffdash/investManagment/invadd.html.twig',[
            'f' =>$form
        ]);
    }
}
