<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @throws \Exception
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request                     $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface      $entityManager,
                             TranslatorInterface         $translator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // Add default role to user
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            try {
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('no-reply@horizon-tech.eu', 'O.do.P'))
                        ->to($user->getEmail())
                        ->subject($translator->trans('register.confirm_subject'))
                        ->htmlTemplate('email/register_confirmation.html.twig')
                );
            } catch (\Exception $e) {
                $form->addError(new FormError($translator->trans('register.error_email')));
            }
            // do anything else you need here, like send an email
            $this->addFlash('success', $translator->trans('register.success_valid'));
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request             $request,
                                    TranslatorInterface $translator,
                                    UserRepository      $userRepository): Response
    {
        $id = $request->query->get('id'); // retrieve the user id from the url
        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('app_login');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
        }
        $this->addFlash('success', $translator->trans('register.verify_email.success'));
        return $this->redirectToRoute('app_login');
    }
}
