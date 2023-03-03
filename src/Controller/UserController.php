<?php

namespace App\Controller;
use App\Entity\Image;
use App\Form\EditType;
use App\Form\RegisterType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
class UserController extends  AbstractController
{

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/login', name: 'app_login')]

    public function login(Request $request,AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
        return $this->render('login.html.twig');
    }




    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher){
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

            if ($form->isValid()) {
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('app_login');
            }

        }
        return $this->render('register.html.twig', [
            'form' => $form->createView(),

        ]);
    }
    #[Route('/user/edit', name: 'app_edit_user')]
    public function editUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imageId = $form->get('image')->getData()) {
                $user->setImage($em->getRepository(Image::class)->find($imageId));
            }
            if ($user->getPlainPassword()) {
                $user->setPassword($hasher->hashPassword($user, $user->getPlainPassword()));
            }
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('user_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}