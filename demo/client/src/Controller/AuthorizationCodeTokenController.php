<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthorizationCodeTokenController extends AbstractController
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    #[Route('/auth/token', 'auth_token')]
    public function __invoke(Request $request): Response
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                'http://demo.auth-server.local:8080/realms/master/protocol/openid-connect/token',
                [
                    'body' => [
                        'grant_type' => 'authorization_code',
                        'client_id' => 'oauth_demo',
                        'client_secret' => 'ZpEMO7bOG8Cg0dnIdFwXJJZooe4bmzDv',
                        'redirect_uri' => 'http://demo.client.local/auth/callback',
                        'code' => $request->query->get('code', null)
                    ]
                ]
            );
            $token = $response->toArray();
            $accessToken = json_decode(base64_decode(explode('.', $token['access_token'])[1]), true);
            $refreshToken = json_decode(base64_decode(explode('.', $token['refresh_token'])[1]), true);


            return $this->render(
                'pages/auth_code_token.html.twig',
                [
                    'result' => $token,
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                ]);
        } catch (\Exception $exception) {
            dd($exception);
        }
    }
}
