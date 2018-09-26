<?php

namespace App\Controller;

use App\Entity\Auction;
use App\Form\AuctionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuctionController extends Controller
{
    /**
     * @Route("/", name="auction_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        $auctions = $this->getDoctrine()->getRepository(Auction::class)->findAll();
        return $this->render("Auction/index.html.twig", ["auctions" =>$auctions]);
    }

    /**
     * @Route("/{id}", name="auction_details")
     *
     * @param $id
     * @return Response
     */
    public function detailsAction($id)
    {
        $auction = $this->getDoctrine()->getRepository(Auction::class)->findOneBy(["id"=>$id]);
        return $this->render("Auction/details.html.twig");
    }

    /**
     * @Route("/auction/add", name="auction_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $auction = new Auction();
        $form = $this->createForm(AuctionType::class, $auction);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auction);
            $entityManager->flush();
            return $this->redirectToRoute("auction_index");
        }
        return $this->render("Auction/add.html.twig", ["form" => $form->createView()]);
    }
}