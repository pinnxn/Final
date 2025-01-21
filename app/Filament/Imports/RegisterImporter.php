<?php

namespace App\Filament\Imports;

use App\Models\Register;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class RegisterImporter extends Importer
{
    protected static ?string $model = Register::class;

    private static $importCount = 0;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('prefix_name')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('lastname')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nickname')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('id_card')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('date_of_birth')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('gender')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('age')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('nationality')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('ethnicity')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('address')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('district')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('province')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('postcode')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('shipping_address')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('shipping_address_detail'),
            ImportColumn::make('phone_number')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('line_id')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('facebook')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('name_parent')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('phone_parent')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sick')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('sick_detail'),
            ImportColumn::make('name_emergency_contact')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('phone_emergency_contact')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('food_allergy')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('food_allergy_detail'),
            ImportColumn::make('education_status')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('name_education')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('address_education')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('province_education')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('study_plan')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('gpax')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('gpa_english')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('gpa_maths')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('experience')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('experience_other'),
            ImportColumn::make('reward')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('hobby')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('link_intro')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('link_transcript')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('link_portfolio')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('link_egd')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('news')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('news_detail'),
            ImportColumn::make('pdpa')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('condition')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function getValidationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            // ... add rules for other fields ...
        ];
    }

    public function resolveRecord(): ?Register
    {
        //return new Register();
        // Validate the data before processing
        $validator = validator($this->data, $this->getValidationRules());
        
        if ($validator->fails()) {
            Log::warning('Validation failed for import record', [
                'errors' => $validator->errors()->toArray(),
                'data' => $this->data
            ]);
            return 'error';
        }

        try {
            $record = new Register();
            // ... record creation logic ...
            
            self::$importCount++;
            Log::info('Import progress', [
                'total_processed' => self::$importCount,
                'current_record' => $this->data
            ]);

            return $record;
        } catch (\Exception $e) {
            Log::error('Import failed', [
                'error' => $e->getMessage(),
                'data' => $this->data
            ]);
            
            return null;
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your register import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
