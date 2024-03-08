<?php

namespace App\Controller;
use app\Entity\Compte;
use App\Entity\FinancialHubPortfolio;
use App\Entity\Pret;
use App\Form\FinancialHubPortfolioType;
use App\Form\PretType;
use App\Repository\CompteRepository;
use App\Repository\FinancialHubInvestRepository;
use App\Repository\FinancialHubPortfolioRepository;
use App\Repository\PretRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ClientdashController extends AbstractController
{
    #[Route('/clientdash', name: 'app_clientdash')]
    public function index(): Response
    {
        return $this->render('clientdash/index.html.twig', [
            'controller_name' => 'ClientdashController',
        ]);
    }

    #[Route('/client',name:'client_app')]
    public function indexclient():Response
    {
        return $this->render('clientdash/client/client.html.twig');
    }

    #[Route('/clientPro',name:'clientPro_app')]
    public function indexclientPro():Response
    {
        return $this->render('clientdash/profile/client-profile.html.twig');
    }

    #[Route('/clientCon',name:'clientCon_app')]
    public function indexadminContact():Response
    {
        return $this->render('clientdash/contact/client-contact.html.twig');
    }

    #[Route('/Liste/{id}', name: 'prete')]
    public function liste(PretRepository $em,CompteRepository $c,$id){
        $client=$c->find($id);

        if(!$client){

        }else{
            $prets=$em->findall();
        }
        return $this->render('clientdash/pret/Pret.html.twig'   ,[
            'pret'=>$prets,
        ])   ;


    }
    #[Route('/List', name: 'pret')]
    public function list(PretRepository $em,Request $req)
    {

        $type=$req->get('type');
        $author = $em->findAll();


        if($req->get('ajax')) {
            $author=$em->findByT($type);
            return new JsonResponse([
                'content'=> $this->renderView('clientdash/pret/Pret.html.twig', [
                    'pret' => $author,

                ])
            ]);
        }

        return $this->render('clientdash/pret/Pret.html.twig', [
            'pret' => $author,

        ]);
    }
    #[Route('/add', name: 'addd')]
    public function add(Request $request,){
        $pret=new Pret();

        $pret->setPretId(null);
        $pret->setStatus(false);
        $pret->setAccepte(false);
        $pret->setRefuse(false);

        $form=$this->createForm(PretType::class, $pret);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            if ($pret->getMontant()>10000){        $pret->calcule();
                $em=$this->getDoctrine()->getManager();
                $em->persist($pret);
                $em->flush();
                return $this->redirectToRoute('pret');
            }else{
                $this->addFlash('failed', "Credit can't be inferieur to 10000 TND");
                return $this->redirectToRoute('addd');
            }

        }
        return $this->render('clientdash/pret/add.html.twig' ,[
            'form' =>$form->createView(),
        ]);

}

    #[Route('/calcule', name: 'calcule')]
public function calcule(Request $request ,PretRepository $em)
{
$pret=new Pret();
$pret->calcule();
    $author = $em->findAll();

    return $this->render('clientdash/pret/add.html.twig', [
        'pret' => $author,

    ]);


}


    #[Route('/update/{id}', name: 'updatee')]
    public function update(PretRepository $em ,Request $request,$id ){
        $pret=$em->find($id);
        $form=$this->createForm(PretType::class, $pret);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($pret);
            $em->flush();
            return $this->redirectToRoute('pret');
        }
        return $this->render('clientdash/pret/update.html.twig'   ,[
            'form'=>$form->createView(),

        ]);




    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id,PretRepository $repo)
    {
        $pret=new Pret();

        $data=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        return $this->redirectToRoute(('pret'));
    }


    /****************************** ********************** anwer yahyawii ************************* **/


    #[Route('/clientinvList',name:'clientinvList_app')]
    public function invList(FinancialHubInvestRepository $repo):Response
    {
        $list=$repo->findAll();
        return $this->render('clientdash/portfolio/clientpor.html.twig',[
            'list'=>$list
        ]);
    }

    #[Route('/portdetal',name:'portdetal_app')]
    public function portdetal():Response
    {
        return $this->render('clientdash/portfolio/portdetail.html.twig');
    }

    #[Route('/portedit/{id}',name:'portedit_app')]
    public function editp(ManagerRegistry $manager, $id,Request $req, FinancialHubPortfolioRepository $repo): Response
    {
        $em=$manager->getManager();
        $portf=$repo->find($id);
        $form=$this->createForm(FinancialHubPortfolioType::class,$portf);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($portf);
            $em->flush();
            return $this->redirectToRoute('myportfolio_app');
        }
        return $this->renderForm('clientdash/portfolio/portedit.html.twig',[
            'f'=>$form
        ]);
    }

    #[Route('/portdelete/{id}',name:'portdelete_app')]
    public function portdelete(ManagerRegistry $manager,$id,FinancialHubPortfolioRepository $repo):Response
    {
        $em=$manager->getManager();
        $portfo=$repo->find($id);
        $em->remove($portfo);
        $em->flush();
        return $this->redirectToRoute('myportfolio_app');
    }

    #[Route('/portadd',name:'portadd_app')]
    public function addPort(Request $req,ManagerRegistry $manager):Response
    {
        $em=$manager->getManager();
        $portfolio=new FinancialHubPortfolio();
        $form=$this->createForm(FinancialHubPortfolioType::class, $portfolio);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($portfolio);
            $em->flush();
            return $this->redirectToRoute('myportfolio_app');
        }
        return $this->renderForm('clientdash/portfolio/portadd.html.twig',[
            'f' =>$form
        ]);
    }

    #[Route('/myportfolio',name:'myportfolio_app')]
    public function listportfolio(FinancialHubPortfolioRepository $repo): Response
    {
        $listp=$repo->findAll();
        return $this->render('clientdash/myport/myportfolio.html.twig',[
            'list'=> $listp
        ]);
    }

    #[Route('/clientroi',name:'clientroi_app')]
    public function indexclientRoi():Response
    {
        return $this->render('clientdash/ROI/roi.html.twig');
    }

    #[Route('/pdf_port',name:'pdf_port')]
    public function pdfgenerate(Request $req,FinancialHubPortfolioRepository $repo):Response
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

        $listp=$repo->findAll();

        $dompdf->setHttpContext($context);
        $html=$this->	renderView('clientdash/myport/tablePortfolio.html.twig',[
            'list'=> $listp
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $fichier='list='.'.pdf';

        $dompdf->stream($fichier,[
            'Attachement'=>true
        ]);
        return new Response();
    }




}