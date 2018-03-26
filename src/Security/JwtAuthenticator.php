<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;
    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManagerInterface $em)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
    }

    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
        'Bearer',
        'Authorization'
        );

        $token = $extractor->extract($request);
        if (!$token) {
            return;
        }

        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
      try {
          $data = $this->jwtEncoder->decode($credentials);
      } catch (JWTDecodeFailureException $e) {
          throw new CustomUserMessageAuthenticationException('Invalid Token');

          $email = $data['email'];
          return $this->em
              ->getRepository(User::class)
              ->findOneBy(['email' => $email]);
      }
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
      return new JsonResponse([
         'message' => $exception->getMessageKey()
       ], 401);
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return;
    }
    public function supportsRememberMe()
    {
      return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
      $data = array(
          'message' => 'Wymagane uwierzytelnienie'
      );

      return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
