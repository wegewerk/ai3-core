<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\RouteResult;

/**
 * Implements the ZakAi Dashboard
 */
#[AsController]
class Ai3Controller extends AbstractBackendController
{
    /**
     * @throws RouteNotFoundException
     */
    public function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->initialize($request);
        $this->buildButtonBar();
        return $this->dashboardAction();
    }

    public function dashboardAction(): ResponseInterface
    {

        $this->view->assign('pid', $this->request->getQueryParams()['id'] ?? $this->request->getAttribute('site')->getRootPageId());
        return $this->view->renderResponse('Ai3/Dashboard');
    }

    public function buildButtonBar()
    {
        /**
         * @var $route RouteResult
         */
        $identifier = $this->request->getAttribute('route')->getOption('_identifier');
        $buttonBar = $this->view->getDocHeaderComponent()->getButtonBar();
        $buttonBar->addButton(
            $this->buildButton(
                'actions-menu',
                'tx_ai3.module.actionmenu.dashboard',
                'btn-md rounded btn-default ' . ($identifier == 'ai3_dashboard' ? 'active' : ''),
                'ai3_dashboard'
            )
        );
        $buttonBar->addButton(
            $this->buildButton(
                'ai3-extension',
                'tx_ai3.module.actionmenu.zakai',
                'btn-md mx-2 rounded btn-default ' . ($identifier == 'ai3_zakai' ? 'active' : ''),
                'ai3_zakai'
            )
        );
        $buttonBar->addButton(
            $this->buildButton(
                'actions-cog-alt',
                'tx_ai3.module.actionmenu.generateTask',
                'btn-md rounded  btn-default ' . ($identifier == 'ai3_generatetask' ? 'active' : ''),
                'ai3_generatetask'
            )
        );
    }

}
