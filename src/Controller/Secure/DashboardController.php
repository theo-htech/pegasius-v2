<?php
namespace App\Controller\Secure;

use App\Repository\SurveyRepository;
use App\Service\YamlSurveyParse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    public function __construct(private YamlSurveyParse $surveyParse,
                                private SurveyRepository $surveyRepository)
    {
    }

    #[Route('/', name : 'app_index')]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        $user = $this->getUser();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($user->isAdmin()) {
            $allSurvey = $this->surveyRepository->findAllfromUserWhoHaveAdminAccess();
        } else {
            $allSurvey = $this->surveyRepository->findAllfromUser($user);
        }
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'surveys' => $allSurvey
        ]);
    }

 
}
