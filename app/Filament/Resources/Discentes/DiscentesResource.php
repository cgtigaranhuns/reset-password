<?php

namespace App\Filament\Resources\Discentes;

use App\Filament\Resources\Discentes\Pages\ManageDiscentes;
use App\Models\Discente;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class DiscentesResource extends Resource
{
    protected static ?string $model = Discente::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';
    protected static string|BackedEnum|null $activeNavigationIcon = 'heroicon-s-identification';
    protected static ?string $recordTitleAttribute = 'Discentes';
    protected static ?string $slug = 'discentes';
    protected static ?string $modelLabel = 'Discente';
    protected static ?string $navigationLabel = 'Discentes';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('matricula')
                    ->label('Matrícula')
                    ,
                TextEntry::make('nome')
                    ->label('Nome')
                    ,
                TextEntry::make('email')
                    ->label('E-mail')
                    ,
                TextEntry::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->date('d/m/Y')
                    ,
                TextEntry::make('curso.nome')
                    ->label('Curso')
                    ,
                TextEntry::make('situacao')
                    ->label('Status da Matrícula')
                    ,
                textEntry::make('telefone')
                    ->label('Telefone')
                    ,
            ])
            ->columns(
                [
                    'default' => 1,  // Celular: 1 coluna
                    'sm' => 1,       // Celular landscape: 1 coluna
                    'md' => 2,       // Tablet: 2 colunas
                    'lg' => 3,       // Laptop: 3 colunas
                    'xl' => 3,       // Desktop: 4 colunas
                    '2xl' => 3,      // Tela grande: 4 colunas
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultPaginationPageOption(25)

            ->recordTitleAttribute('Discentes')
            ->columns([
                
                TextColumn::make('matricula')->searchable()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('nome')->searchable()->toggleable(),
                
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('data_nascimento')
                    ->date('d/m/Y')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('curso.nome')
                    ->label('Curso')
                    ->searchable()
                    ->limit(25)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('situacao')
                    ->label('Status da Matrícula')
                    ->searchable()
                    ->limit(15)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->label('')
                    ->tooltip('Visualizar'),
                
            ])
            
            ;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageDiscentes::route('/'),
        ];
    }
}