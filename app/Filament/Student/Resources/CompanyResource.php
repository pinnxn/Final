<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\CompanyResource\Pages;
use App\Filament\Student\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Student\Resources\SpatieMediaLibraryImageColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextColumnSize as Size;
use Filament\Tables\Columns\TextColumnSize;



class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Grid::make()
                ->columns(1)
                ->schema([
                    ImageColumn::make('logo')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png'))
                    ->height(100)
                    ->extraImgAttributes([
                        'class' => 'object-contain',
                        'loading' => 'lazy',
                    ]),
                    TextColumn::make('name')
                        ->weight(FontWeight::SemiBold)
                        ->size('lg'),
                    TextColumn::make('address')
                    ->icon('heroicon-m-map-pin'),
                    TextColumn::make('phone')
                    ->icon('heroicon-m-phone'),
                    TextColumn::make('email')
                    ->icon('heroicon-m-envelope'),
                    TextColumn::make('website')
                    ->url(fn (string $state): string => $state)
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-globe-alt')
                    ->color('primary'),
                ])
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ]);
            // ->actions([
            //     //Tables\Actions\EditAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanies::route('/'),
            //'create' => Pages\CreateCompany::route('/create'),
            // 'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
