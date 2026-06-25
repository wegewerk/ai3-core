<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use Wegewerk\Ai3Core\Domain\Repository\GenerationTaskRepository;
use Wegewerk\Ai3Core\Service\TranslationService;

#[AsController]
class GenerateTaskController extends Ai3Controller
{
    protected GenerationTaskRepository $generationTaskRepository;
    protected LoggerInterface $logger;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        IconFactory $iconFactory,
        UriBuilder $uriBuilder,
        PageRenderer $pageRenderer,
        FlashMessageService $flashMessageService,
        TranslationService $translationService,
        GenerationTaskRepository $generationTaskRepository,
        LoggerInterface $logger
    ) {
        parent::__construct(
            $moduleTemplateFactory,
            $iconFactory,
            $uriBuilder,
            $pageRenderer,
            $flashMessageService,
            $translationService,
            $logger
        );
        $this->generationTaskRepository = $generationTaskRepository;
        $this->logger = $logger;
    }

    /**
     * @throws RouteNotFoundException
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request, static::class);
        $this->buildButtonBar();
        return $this->manageGenerateTasksAction();
    }

    public function manageGenerateTasksAction(): ResponseInterface
    {
        try {
            $generateTasks = $this->generationTaskRepository->findAll();
            $this->view->assignMultiple([
                'generateTasks' => $generateTasks,
            ]);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->view->addFlashMessage(
                $e->getMessage(),
                $this->translationService->translate('ai3.error.default.title'),
                ContextualFeedbackSeverity::ERROR
            );
        }

        return $this->view->renderResponse('GenerateTask/ManageGenerateTasks');
    }
}
