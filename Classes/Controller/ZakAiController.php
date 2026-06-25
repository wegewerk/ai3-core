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
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Wegewerk\Ai3Core\Api\ZakAiAccount;
use Wegewerk\Ai3Core\Enums\RegistrationStatus;
use Wegewerk\Ai3Core\Service\TranslationService;
use Wegewerk\Ai3Core\Service\ZakAiRegistrationService;

#[AsController]
class ZakAiController extends Ai3Controller
{
    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected IconFactory $iconFactory,
        protected UriBuilder $uriBuilder,
        protected PageRenderer $pageRenderer,
        protected FlashMessageService $flashMessageService,
        protected TranslationService $translationService,
        protected LoggerInterface $logger,
        protected ZakAiRegistrationService $zakAiRegistrationService,
        protected ZakAiAccount $zakAiAccount
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
    }

    /**
     * @throws RouteNotFoundException
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        $this->buildButtonBar();
        return $this->manageZakAiAction($request);
    }

    public function confirmApikey(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        try {
            $apikeySuccessfullyConfirmed = $this->zakAiAccount->confirmApikey();
            if ($apikeySuccessfullyConfirmed) {
                $this->zakAiRegistrationService->setStatus(RegistrationStatus::done);
                $this->zakAiRegistrationService->setSecret('');
                $this->zakAiRegistrationService->setEmail('');
            }

        } catch (\Exception $e) {
            $this->view->assign('registrationError', $e->getMessage());
        }

        return $this->manageZakAiAction($request);
    }

    public function requestApikey(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        $body = $this->request->getParsedBody();
        $secret = $body['secret'] ?? null;
        $email = $body['email'] ?? null;
        $apikeySuccessfullyRequested = false;

        try {
            if (!empty($secret) && !empty($email)) {
                // secret und email temporär in typo3 registry speichern
                $this->zakAiRegistrationService->setSecret($secret);
                $this->zakAiRegistrationService->setEmail($email);
                $apikeySuccessfullyRequested = $this->zakAiAccount->requestApikey($email, $secret);
                $this->view->assign('registration', [ 'secret' => $secret, 'email' => $email ]);
            }
            if ($apikeySuccessfullyRequested) {
                $this->zakAiRegistrationService->setStatus(RegistrationStatus::requested);
            }
        } catch (\Exception $e) {
            $this->view->assign('registrationError', $e->getMessage());
        }
        return $this->manageZakAiAction($request);
    }

    public function checkApikey(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        return $this->manageZakAiAction($request);
    }

    protected function manageZakAiAction(ServerRequestInterface $request): ResponseInterface
    {
        $this->pageRenderer->loadJavaScriptModule('@wegewerk/ai3core/creditsElement.js');
        $this->pageRenderer->loadJavaScriptModule('@wegewerk/ai3core/accountElement.js');
        try {
            $envCheck['serviceKey'] = $this->zakAiAccount->clientHasApikey() ? 'OK' : 'MISSING';
            $envCheck['secret'] = $this->zakAiAccount->clientHasSecret() ? 'OK' : 'MISSING';
            // 1. Prüfen ob Zak_ai registration erfolgt ist
            $status = $this->zakAiRegistrationService->getStatus();
            // wenn kein Status gefunden wurde, serviceKey und secret aber gesetzt sind, dann registration auf 'done' setzen
            if ($status == RegistrationStatus::initial && $envCheck['serviceKey'] == 'OK' && $envCheck['secret'] == 'OK') {
                $this->zakAiRegistrationService->setStatus(RegistrationStatus::done);
            }
            // wenn die Registration schon mal angefangen wurde, die Umgebungsvariablen serviceKey und secret aber leer sind
            // und auch kein temporäres secret in der Registry steht
            // dann registration auf 'initial' setzen
            if ($status != RegistrationStatus::initial && $envCheck['serviceKey'] == 'MISSING' && $envCheck['secret'] == 'MISSING' && empty($this->zakAiRegistrationService->getSecret())) {
                $this->zakAiRegistrationService->setStatus(RegistrationStatus::initial);
            }
            if (!empty($this->zakAiRegistrationService->getEmail()) && !empty($this->zakAiRegistrationService->getSecret())) {
                $this->view->assign(
                    'registration',
                    [
                        'secret' => $this->zakAiRegistrationService->getSecret(),
                        'email'  => $this->zakAiRegistrationService->getEmail(),
                    ]
                );
            }
            $this->view->assign('zakaiconfigurationresolution', [
                'serviceKeyRef' => 'ZAKAI_API_KEY',
                'secretRef' => 'ZAKAI_SECRET',
            ]);
            $status = $this->zakAiRegistrationService->getStatus();
            $envCheck['registrationstatus'] = $status->value;
            $this->view->assign('envCheck', $envCheck);

        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            $this->view->addFlashMessage(
                $e->getMessage(),
                $this->translationService->translate('ai3.error.default.title'),
                ContextualFeedbackSeverity::ERROR
            );
        }

        $this->view->assign(
            'pid',
            $this->request->getQueryParams()['id'] ?? $this->request->getAttribute('site')
            ->getRootPageId()
        );
        return $this->view->renderResponse('ZakAi/ManageZakAi');
    }

    private function showSaveMessage(): void
    {
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            'Ihre Einstellungen wurden gespeichert.',
            'Gespeichert',
            ContextualFeedbackSeverity::OK,
            true
        );

        $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
        $queue = $flashMessageService->getMessageQueueByIdentifier(FlashMessageQueue::NOTIFICATION_QUEUE);
        $queue->enqueue($flashMessage);
    }
}
