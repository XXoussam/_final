<?php

namespace App\Controller\crypto_space;

use App\Entity\PostP2p;
use App\Repository\AccountRepository;
use App\Repository\ClientRepository;
use App\Repository\PostP2pRepository;
use App\services\AppExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoSpaceController extends AbstractController
{
    #[Route('/crypto/space', name: 'app_crypto_space')]
    public function index(): Response
    {
        return $this->render('crypto_space/crypto_space.html.twig', [
            'controller_name' => 'CryptoSpaceController',
        ]);
    }

    #[Route('/crypto/space/wallet', name: 'app_crypto_space_wallet')]
    public function wallet(): Response
    {
        return $this->render('crypto_space/my_crypto_wallet/wallet.html.twig', [
            'controller_name' => 'CryptoSpaceController',
        ]);
    }

    #[Route('/crypto/space/p2p', name: 'app_crypto_space_p2p')]
    public function p2p(Request $request,PostP2pRepository $postP2pRepository): Response
    {
        // Create a form
        $form = $this->createFormBuilder()
            ->add('amount', NumberType::class)
            ->add('price', NumberType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Post it!',
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();

        // Handle form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // Handle form data, e.g., save to the database
            dump($data);
            $post = new PostP2p();
            $post->setAmount($data['amount']);
            $post->setPrice($data['price']);
            $post->setStatus('OPEN');
            $post->setowner($this->getUser()->getUsername());
            $post->setCreatedAt(new \DateTimeImmutable());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();


            $javascriptCode = <<<EOD
<script>
    // main.js

    function sendEthToAddress() {
        // Check if MetaMask is installed
        if (typeof window.ethereum !== 'undefined') {
            console.log('MetaMask is installed!');
            const web3 = new Web3(window.ethereum);

            // Prompt user to connect their MetaMask account
            window.ethereum.request({ method: 'eth_requestAccounts' })
                .then(function(accounts) {
                    // User approved the account access, you can now use the accounts array to get the user's account
                    const userAccount = accounts[0];
                    console.log('User account:', userAccount);

                    // Retrieve the balance of the user's account
                    web3.eth.getBalance(userAccount)
                        .then(function(balance) {
                            // Convert the balance from Wei to Ether
                            const balanceInEther = web3.utils.fromWei(balance, 'ether');
                            console.log('Balance:', balanceInEther, 'ETH');

                            // Send Ether to another address
                            const receiverAddress = '0x15c0423b762e122fe85b050A6F5e160D72D1Fc34';
                            const amountToSend = web3.utils.toWei('${data['amount']}', 'ether');
                            // Send transaction
                            web3.eth.sendTransaction({
                                from: userAccount,
                                to: receiverAddress,
                                value: amountToSend
                            })
                                .on('transactionHash', function(hash) {
                                    console.log('Transaction Hash:', hash);
                                    // Transaction sent, you can handle success here
                                })
                                .on('error', function(error) {
                                    // Handle error
                                    console.error('Error sending transaction:', error);
                                });
                        })
                        .catch(function(error) {
                            // Handle error
                            console.error('Error getting balance:', error);
                        });
                })
                .catch(function(error) {
                    // Handle error
                    console.error('Error requesting accounts:', error);
                });
        } else {
            console.log('MetaMask is not installed!');
        }
    }

    sendEthToAddress();
</script>
EOD;


            return $this->redirectToRoute('app_crypto_space_p2p', ['javascriptCode' => $javascriptCode]);
        }


        $posts = $postP2pRepository->findAll();
        $my_posts = [];
        $others_posts = [];
        foreach ($posts as $post) {
            if ($post->getowner() == $this->getUser()->getUsername()) {
                $my_posts[] = $post;
            }
            else {
                if ($post->getStatus() == 'OPEN')
                    $others_posts[] = $post;
            }
        }



        dump($my_posts);

        return $this->render('crypto_space/p2p_space/p2p.html.twig', [
            'controller_name' => 'CryptoSpaceController',
            'form' => $form->createView(),
            'posts' => $my_posts,
            'others_posts' => $others_posts,
            'javascriptCode' => $request->query->get('javascriptCode')
        ]);
    }

    #[Route('/crypto/space/p2p/buy_eth/{id}', name: 'app_crypto_space_p2p_buy_eth')]
    public function buyEth($id,
                           PostP2pRepository $postP2pRepository,
                           AppExtension $appExtension,
                           AccountRepository $accountRepository,
                           ClientRepository $clientRepository): Response
    {
        $post = $postP2pRepository->find($id);
        $client = $appExtension->getClient();
        $account = $accountRepository->getByTypeAndOwnerId('SAVINGS', $client->getId());
        $account->setBalance($account->getBalance() - $post->getPrice());

        $em = $this->getDoctrine()->getManager();
        $em->persist($account);
        $em->flush();

        $post->setStatus('SOLD');
        $em->persist($post);
        $em->flush();

        $client_beneficiary = $clientRepository->findByEmail($post->getowner());
        $account_beneficiary = $accountRepository->getByTypeAndOwnerId('SAVINGS', $client_beneficiary->getId());
        $account_beneficiary->setBalance($account_beneficiary->getBalance() + $post->getPrice());
        $em->persist($client_beneficiary);
        $em->flush();





        return $this->redirectToRoute('app_crypto_space_p2p');
    }
}
