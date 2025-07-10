<?php

namespace Obelaw\Obridge\Filament\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Obelaw\Obridge\Filament\Resources\ObridgeResource\ListObridge;
use Obelaw\Obridge\Models\Obridge;
use Obelaw\Permit\Attributes\Permissions;
use Obelaw\Permit\Traits\PremitCan;

#[Permissions(
    id: 'permit.accounting.account_types.viewAny',
    title: 'Account Types',
    description: 'Access on account types at accounting',
    permissions: [
        'permit.accounting.account_types.create' => 'Can Create',
        'permit.accounting.account_types.edit' => 'Can Edit',
        'permit.accounting.account_types.delete' => 'Can Delete',
    ]
)]
/**
 * Represents a Price List resource for Filament.
 *
 * This class defines the form, table, and other aspects of how Price Lists
 * are managed within the Filament admin panel.
 */
class ObridgeResource extends Resource
{
    use PremitCan;

    protected static ?array $canAccess = [
        'can_viewAny' => 'permit.accounting.account_types.viewAny',
        'can_create' => 'permit.accounting.account_types.create',
        'can_edit' => 'permit.accounting.account_types.edit',
        'can_delete' => 'permit.accounting.account_types.delete',
    ];

    protected static ?string $model = Obridge::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('A unique identifier for this Obridge connection'),

                TextInput::make('secret')
                    ->required()
                    ->maxLength(255)
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => $state ?: \Obelaw\Obridge\Models\Obridge::generateSecret())
                    ->placeholder('Leave empty to auto-generate')
                    ->helperText('Authentication secret. Leave empty to auto-generate a secure secret')
                    ->hiddenOn('edit'),

                TextInput::make('description')
                    ->maxLength(1000)
                    ->columnSpanFull()
                    ->helperText('Optional description for this Obridge connection'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Enable or disable this Obridge connection'),

            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Never'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All connections')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListObridge::route('/'),
        ];
    }
};
