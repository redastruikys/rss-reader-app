<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    /**
     * @Route("/api/check-email", name="api_check_email", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function checkEmail(Request $request): Response
    {
        $email = $request->get('email') ?? 'redas.truikys@gmail.com';
        $user = $this->userRepository->findUserByEmail($email);
        $errorMessage = $user ? $this->translator->trans('api.error.email_already_exists') : null;

        return $this->getResponseObject([], $errorMessage);
    }

    private function getResponseObject(array $data, string $error = null): Response
    {
        return new JsonResponse([
            'response' => [
                'data' => $data,
                'error' => empty($error) ? null : $error,
                'valid' => empty($error),
            ]
        ]);
    }
}
