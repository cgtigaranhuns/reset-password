<?php

namespace App\Filament\Resources\Discentes\Pages;

use App\Filament\Resources\Discentes\DiscentesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageDiscentes extends ManageRecords
{
    protected static string $resource = DiscentesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
