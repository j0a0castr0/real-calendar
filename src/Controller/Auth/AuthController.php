<?php

namespace App\Controller\Auth;

use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    private $cognito;
    private $userPoolId;
    private $clientId;

    public function __construct(string $region, string $userPoolId, string $clientId)
    {
        $this->cognito = new CognitoIdentityProviderClient([
            'region' => $region,
            'version' => 'latest',
        ]);
        $this->userPoolId = $userPoolId;
        $this->clientId = $clientId;
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        try {
            $result = $this->cognito->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_USER_PASSWORD_AUTH',
                'UserPoolId' => $this->userPoolId,
                'ClientId' => $this->clientId,
                'AuthParameters' => [
                    'USERNAME' => $email,
                    'PASSWORD' => $password,
                ],
            ]);

            return new JsonResponse($result->get('AuthenticationResult'));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
