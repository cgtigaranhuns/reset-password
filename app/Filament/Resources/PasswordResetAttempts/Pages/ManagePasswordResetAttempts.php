<?php

namespace App\Filament\Resources\PasswordResetAttempts\Pages;

use App\Filament\Resources\PasswordResetAttempts\PasswordResetAttemptResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePasswordResetAttempts extends ManageRecords
{
    protected static string $resource = PasswordResetAttemptResource::class;
    protected static ?string $title = 'Tentativas de recuperação de senha';

    protected function getHeaderActions(): array
    {
        return [
          
        ];
    }
}