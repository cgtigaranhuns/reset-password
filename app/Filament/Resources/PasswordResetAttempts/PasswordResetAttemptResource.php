<?php

namespace App\Filament\Resources\PasswordResetAttempts;

use App\Filament\Resources\PasswordResetAttempts\Pages\ManagePasswordResetAttempts;
use App\Models\PasswordResetAttempt;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PasswordResetAttemptResource extends Resource
{
    protected static ?string $model = PasswordResetAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('enrollment')
                    ->label('Matrícula')
                    ->required(),
                TextInput::make('full_name')
                    ->label('Nome Completo'),
                TextInput::make('cpf_masked')
                    ->label('CPF'),
                TextInput::make('email_masked')
                    ->label('Email')
                    ->email(),
                TextInput::make('enrollment_status')
                    ->label('Status da Matrícula'),
                TextInput::make('enrollment_status_code')
                    ->label('Código do Status da Matrícula'),
                TextInput::make('ip_address')
                    ->label('IP')
                    ->required(),
                TextInput::make('user_agent')
                    ->label('User Agent'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'success' => 'Success',
                        'failed_not_found' => 'Failed not found',
                        'failed_status_invalid' => 'Failed status invalid',
                        'failed_cpf_mismatch' => 'Failed cpf mismatch',
                        'failed_rate_limited' => 'Failed rate limited',
                        'failed_ldap_not_found' => 'Failed ldap not found',
                        'failed_ldap_error' => 'Failed ldap error',
                        'failed_network' => 'Failed network',
        ])
                    ->default('failed_not_found')
                    ->required(),
                TextInput::make('failure_reason')
                    ->label('Motivo da Falha'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('enrollment')
                    ->label('Matrícula')
                    ->placeholder('-'),
                TextEntry::make('full_name')
                    ->label('Nome Completo')
                    ->placeholder('-'),
                TextEntry::make('cpf_masked')
                    ->label('CPF')  
                    ->placeholder('-'),
                TextEntry::make('email_masked')
                    ->label('Email')
                    ->placeholder('-'),
                TextEntry::make('enrollment_status')    
                    ->label('Status da Matrícula')
                    ->placeholder('-'),
                TextEntry::make('enrollment_status_code')
                    ->label('Código do Status da Matrícula')
                    ->placeholder('-'),
                TextEntry::make('ip_address')
                    ->placeholder('-')  
                    
                    ->label('IP'),
                TextEntry::make('user_agent')
                    ->label('User Agent')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('failure_reason')
                    ->label('Motivo da Falha')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->label('Criado em')
                    ->dateTime(format: 'd/m/Y')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment')
                    ->label('Matrícula')
                    ->sortable()

                    ->searchable(),
                TextColumn::make('full_name')
                    ->label('Nome Completo')
                    ->searchable(),
                TextColumn::make('cpf_masked')
                    ->label('CPF')
                    ->searchable(),
                TextColumn::make('email_masked')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('enrollment_status')
                    ->label('Status da Matrícula')
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),
                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('failure_reason')
                    ->label('Motivo da Falha')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ;
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePasswordResetAttempts::route('/'),
        ];
    }
}