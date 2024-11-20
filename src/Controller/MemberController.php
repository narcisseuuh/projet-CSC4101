<?php

namespace App\Controller;

use App\Entity\Member;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class MemberController extends AbstractController
{
    #[Route('/member', name: 'app_member', methods:['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'etes pas admin...')]
    public function index(MemberRepository $MemberRepository): Response
    {
        return $this->render('member/index.html.twig', [
            'members' => $MemberRepository->findAll(),
        ]);
    }

    #[Route('/member/{id}', name: 'app_member_show', methods: ['GET'])]
    public function show(Member $member): Response
    {
        return $this->render('member/show.html.twig', [
            'member' => $member,
        ]);
    }
}
