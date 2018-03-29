<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\ObjectGenerator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormInterface;
use App\Api\FormValidator;
use App\Api\FormProcessor;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;

class RegistrationController extends Controller
{

    /**
     * @Route("/api/register", name="registration")
     * @Method("POST")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, FormValidator $validator, FormProcessor $formProcessor)
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $data['plainPassword']['first']);
            $user->setPassword($password);
            $user->generateActivationCode();

            $this->sendConfirmationEmail($data['email'], $user->getActivationCode(), $mailer);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json("Dodano nowego użytkownika", 201);
        }
    }

    public function sendConfirmationEmail($email, $activationCode, \Swift_Mailer $mailer)
    {
        $activateUrl = $this->get('router')->generate('user_activation', array(
              'activationCode' => $activationCode
          ));

        $message = (new \Swift_Message('Witamy w naszym serwisie moje cele!'))
            ->setFrom('lasekdeveloper@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    array('activeLink' => $activateUrl)
                ),
                'text/html'
            );

        $mailer->send($message);
    }

    /**
     * @Route("/api/activate/{activationCode}", name="user_activation")
     */
    public function activationAction($activationCode, ObjectGenerator $generator)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByActivationCode($activationCode);

        if (!$user) {
            throw $this->createNotFoundException(
                'Nie znaleziono użytkownika'
            );
        }
        if (!$user->getIsActive()) {
            $user->setIsActive(1);
            $generator->generateCategory($user);

            $em->flush();
        }

        return $this->redirectToRoute('/');
    }
}
