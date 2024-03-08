<?php

namespace App\Controller;

use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Repository\PretRepository;
use App\Repository\RemboursementRepository;
use App\service\MailerService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/loan_management', name: 'loan_management')]
class StaffdashController extends AbstractController
{

    #[Route('/Listt', name: 'prett')]
    public function list(PretRepository $emm)
    {

        $author = $emm->findAll();
        $personel=[];
        $petitEnt=[];
        $entreprise=[];
        foreach ($author as $p){
            if($p->getType()=='Entreprise'){
                $entreprise[]=$p->getId();
            }
            if($p->getType()=='Personnel'){
                $personel[]=$p->getId();
            }
            if($p->getType()=='Petit-Business'){
                $petitEnt[]=$p->getId();
            }

        }

        return $this->render('responsible_loan_home/Pret/pret.html.twig', [
            'pret' =>$author,
            'e'=>$entreprise,
            'p'=>$personel,
            'pe'=>$petitEnt
        ]);
    }

#[Route('/accepte/{id}', name: 'accepte')]
public function accepte( PretRepository $em,$id){

  $pret=$em->find($id);
        $pret->setAccepte(true);
        $pret->setRefuse(false);
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('loan_managementprett');
}

#[Route('/refuse/{id}', name: 'refuse')]
public function refuse(PretRepository $em,$id)
{
    $pret=$em->find($id);
    $pret->setRefuse(true);
    $pret->setAccepte(false);
    $em=$this->getDoctrine()->getManager();
    $em->flush();

    return $this->redirectToRoute('loan_managementprett');
}




    #[Route('/listr', name: 'listr')]
    public function lister(RemboursementRepository $emm)
    {

        $author = $emm->findAll();

        return $this->render('responsible_loan_home/Rembourssement/list.html.twig', [
            'pret' =>$author,

        ]);
    }
    #[Route('/listra', name: 'listra')]
    public function listera(PretRepository $emm)
    {

        $author = $emm->findAll();

        return $this->render('responsible_loan_home/Pret/accepte.html.twig', [
            'pret' =>$author,

        ]);
    }
    #[Route('/listrr', name: 'listrr')]
    public function listerr(PretRepository $emm)
    {

        $author = $emm->findAll();

        return $this->render('responsible_loan_home/Pret/refuse.html.twig', [
            'pret' =>$author,

        ]);
    }





    #[Route('/addr', name: 'adddr')]
    public function add(Request $request){
        $prett=new Remboursement();
$prett->setStatus(false);

        $form=$this->createForm(RemboursementType::class, $prett);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($prett);
            $em->flush();
            return $this->redirectToRoute('loan_managementlistr');
        }
        return $this->render('responsible_loan_home/Rembourssement/add.html.twig' ,[
            'form' =>$form->createView(),
        ]);

    }


    #[Route('/updater/{id}', name: 'updateer')]
    public function updater(RemboursementRepository $em ,Request $request,$id ){
        $pret=$em->find($id);
        $form=$this->createForm(RemboursementType::class, $pret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($pret);
            $em->flush();
            return $this->redirectToRoute('loan_managementlistr');
        }
        return $this->render('responsible_loan_home/Rembourssement/update.html.twig'   ,[
            'form'=>$form->createView(),

        ]);




    }

    #[Route('/deleter/{id}', name: 'deleter')]
    public function delete($id,PretRepository $repo)
    {
        $pret=new Remboursement();

        $data=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        return $this->redirectToRoute(('listr'));
    }


    #[Route('/pdf',name:'pdf')]
    public function pdfgenerate(Request $req,RemboursementRepository $repo):Response
    {
        $pdfOption = new Options();
        $pdfOption->set('defaultFont','Arial');
        $pdfOption->setIsRemoteEnabled(true);

        $dompdf=new Dompdf($pdfOption);
        $context= stream_context_create([
            'ssl' => [
                'verify_peer'=>False,
                'verify_peer_name'=>False,
                'allow_self_signed'=>True
            ]
        ]);
        $fact=$repo->findAll();


        $dompdf->setHttpContext($context);
        $html=$this->renderView('responsible_loan_home/Rembourssement/table.html.twig',['pret'=>$fact]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $fichier='Rembourssement_Complet.pdf';

        $dompdf->stream($fichier,[
            'Attachement'=>true
        ]);
        return new Response();
    }



    #[Route('/complet/{id}', name: 'complet')]
    public function complet( RemboursementRepository $em,$id){

        $pret=$em->find($id);
        $pret->setStatus(true);

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('loan_managementlistr');
    }



    #[Route('/listc', name: 'listc')]
    public function listc(RemboursementRepository $emm)
    {

        $author = $emm->findAll();

        return $this->render('responsible_loan_home/Rembourssement/complet.html.twig', [
            'pret' =>$author,

        ]);
    }

}
