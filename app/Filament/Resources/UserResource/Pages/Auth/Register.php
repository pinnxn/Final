<?php

namespace App\Filament\Resources\UserResource\Pages\Auth;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use App\Models\Role;
use Filament\Forms\Components\TextInput;
    class Register extends BaseRegister
    {
        protected function getForms(): array
        {
            return [
                'form' => $this->form(
                    $this->makeForm()
                        ->schema([
                            $this->getNameFormComponent(),
                            $this->getEmailFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(), 
                        ])
                        ->statePath('data'),
                ),
            ];

            
        }
    }