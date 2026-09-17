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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PasswordResetAttemptResource extends Resource
{
    protected static ?string $model = PasswordResetAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $modelLabel = 'Tentativa de Reset';
    protected static ?string $navigationLabel = 'Reset de Senha (Auditoria)';
    
    // Recurso somente-leitura: sem create/edit/delete
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
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime(format: 'd/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                TextColumn::make('status')
                    ->label('Resultado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'failed_rate_limited', 'failed_network' => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'success' => 'Sucesso',
                        'failed_not_found' => 'Matrícula não encontrada',
                        'failed_status_invalid' => 'Situação inválida',
                        'failed_cpf_mismatch' => 'CPF não confere',
                        'failed_rate_limited' => 'Bloqueado (rate limit)',
                        'failed_ldap_not_found' => 'Usuário AD não encontrado',
                        'failed_ldap_error' => 'Erro no AD',
                        'failed_network' => 'Fora da rede corporativa',
                        default => $state,
                    }),
                TextColumn::make('ip_address')
                        ->label('IP')
                        ->searchable(),
                
            ])
            ->filters([
                 SelectFilter::make('status')
                    ->label('Resultado')
                    ->options([
                        'success' => 'Sucesso',
                        'failed_not_found' => 'Matrícula não encontrada',
                        'failed_status_invalid' => 'Situação inválida',
                        'failed_cpf_mismatch' => 'CPF não confere',
                        'failed_rate_limited' => 'Bloqueado (rate limit)',
                        'failed_ldap_not_found' => 'Usuário AD não encontrado',
                        'failed_ldap_error' => 'Erro no AD',
                        'failed_network' => 'Fora da rede corporativa',
                    ]),

                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('De'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ]);
            
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePasswordResetAttempts::route('/'),
        ];
    }
}