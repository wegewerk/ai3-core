<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Controller\Ajax;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Core\Http\Response;
use Wegewerk\Ai3Core\Api\ZakAiAccount;

#[\AllowDynamicProperties]
#[AsController]
class AccountController extends AbstractAjaxController
{
    public function __construct(
        LoggerInterface $logger,
        protected ZakAiAccount $zakAiAccount
    ) {
        parent::__construct($logger);
    }

    public function getCredits(ServerRequestInterface $request): ResponseInterface
    {
        return $this->createJsonSuccessResponse(
            new Response(),
            $this->zakAiAccount->queryCredits()
        );
    }
    public function getAccount(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->zakAiAccount->queryAccount();
        return $this->createJsonSuccessResponse(
            new Response(),
            $response['account'] ?? []
        );
    }

}
