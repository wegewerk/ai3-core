<?php

declare(strict_types=1);

namespace Wegewerk\Ai3Core\Enums;

/**
 * Bildet den Status einer Zak_Ai Registration ab
 */
enum RegistrationStatus: string
{
    // Das Zak-AI Modul wurde mindestens ein mal aufgerufen
    case initial = 'Initial';
    // der API Key wurde angefordert
    case requested = 'Requested';
    // der API Key wurde in der Registry hinterlegt
    case done = 'Done';
    // der API Key wurde als ungültig markiert (TO BE IMPLEMENTED in Zak_Ai)
    case revoked = 'Revoked';
}
