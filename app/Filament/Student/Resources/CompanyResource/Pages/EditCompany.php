<?php

namespace App\Filament\Student\Resources\CompanyResource\Pages;

use App\Filament\Student\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
          // Actions\DeleteAction::make(),
        ];
    }
}
