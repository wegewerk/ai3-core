<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Imaging\IconSize;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Wegewerk\Ai3Core\Service\TranslationService;
use Wegewerk\Ai3Core\Template\Components\Buttons\Ai3LinkButton;

class AbstractBackendController
{
    protected ServerRequestInterface $request;
    protected ModuleTemplate $view;

    public function __construct(
        protected ModuleTemplateFactory $moduleTemplateFactory,
        protected IconFactory $iconFactory,
        protected UriBuilder $uriBuilder,
        protected PageRenderer $pageRenderer,
        protected FlashMessageService $flashMessageService,
        protected TranslationService $translationService,
        protected LoggerInterface $logger,
    ) {}

    /**
     * @throws RouteNotFoundException
     */
    public function initialize(ServerRequestInterface $request): void
    {
        $this->request = $request;
        $this->view = $this->moduleTemplateFactory->create($request);
        $this->view->setTitle('AI3');
        $this->view->setFlashMessageQueue($this->flashMessageService->getMessageQueueByIdentifier('ai3.template.flashMessages'));
        $this->view->setModuleId('ai3');

        $this->pageRenderer->addInlineLanguageLabelFile('EXT:ai3_core/Resources/Private/Language/locallang.xlf');
    }

    /**
     * @throws RouteNotFoundException
     */
    protected function buildButton(
        string $iconIdentifier,
        string $translationKey,
        string $classes,
        string $route
    ): Ai3LinkButton {
        $rootPageId = $this->request->getAttribute('site')
            ->getRootPageId();
        $uriParameters = [
            'id' => $this->request->getQueryParams()['id'] ?? $this->id ?? $rootPageId,
        ];
        $url = (string)$this->uriBuilder->buildUriFromRoute($route, $uriParameters);
        $button = GeneralUtility::makeInstance(Ai3LinkButton::class);
        return $button->setIcon($this->iconFactory->getIcon($iconIdentifier, IconSize::SMALL))
            ->setTitle($this->translationService->translate($translationKey))
            ->setShowLabelText(true)
            ->setClasses($classes)
            ->setHref($url);
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    protected function processRequest(ServerRequestInterface $request): ResponseInterface
    {
        return $this->view->renderResponse();
    }

}
