<?php

namespace App\Controller;

use App\Entity\Redirect;
use App\Repository\RedirectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RedirectController extends AbstractController
{
    public function __construct(
        private readonly RedirectRepository  $redirectRepository,
        private readonly HttpClientInterface $client,
        private readonly string              $linkTemplate,
        private readonly string              $linkHost,
    ) {
    }

    #[Route('/{id<[0-9a-fA-F]{40}>}.mp4', name: 'redirect')]
    public function __invoke(Request $request, string $id): Response
    {
        /** @var ?Redirect $redirect */
        $redirect = $this->redirectRepository->find($id);

        if (null === $redirect) {
            throw new NotFoundHttpException('id expired or invalid');
        }

        $response = $this->client->request(
            Request::METHOD_GET,
            str_replace(
                [
                    '{host}',
                    '{id}'
                ],
                [
                    $this->linkHost,
                    $redirect->movieId,
                ],
                $this->linkTemplate
            ),
            [
                // Override some headers
                'headers' => array_merge(
                    $request->headers->all(),
                    [
                        'Host' => $this->linkHost,
                    ]
                ),
            ]
        );

        return new StreamedResponse(function () use ($response) {
            foreach ($this->client->stream($response) as $chunk) {
                print $chunk->getContent();
            }
        }, $response->getStatusCode(), $response->getHeaders(false));
    }
}