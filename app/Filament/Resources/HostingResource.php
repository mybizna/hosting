<?php

namespace Modules\Hosting\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Base\Filament\Resources\BaseResource;
use Modules\Hosting\Models\Hosting;

class HostingResource extends BaseResource
{
    protected static ?string $model = Hosting::class;

    protected static ?string $slug = 'hosting/hosting';

    protected static ?string $navigationGroup = 'Hosting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('http_code')
                    ->numeric()
                    ->default(null),
                Forms\Components\DateTimePicker::make('expiry_date'),
                Forms\Components\DateTimePicker::make('upgrade_date'),
                Forms\Components\DateTimePicker::make('last_upgrade_date'),
                Forms\Components\TextInput::make('log')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('paid'),
                Forms\Components\Toggle::make('completed'),
                Forms\Components\Toggle::make('successful'),
                Forms\Components\Toggle::make('status'),
                Forms\Components\Toggle::make('is_new'),
                Forms\Components\Toggle::make('is_update'),
                Forms\Components\Toggle::make('is_registered'),
                Forms\Components\Toggle::make('is_cpaneled'),
                Forms\Components\Toggle::make('is_installed'),
                Forms\Components\Toggle::make('is_removed'),
                Forms\Components\Toggle::make('is_live'),
                Forms\Components\Toggle::make('is_synced'),
                Forms\Components\TextInput::make('call_counter')
                    ->numeric()
                    ->default(null),
                Forms\Components\Toggle::make('has_error'),
                Forms\Components\TextInput::make('domain_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('package_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payment_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('server_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('partner_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('whmcs_order_id')
                    ->numeric()
                    ->default(null),
                Forms\Components\Toggle::make('is_in_whmcs'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('http_code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiry_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('upgrade_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_upgrade_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('log')
                    ->searchable(),
                Tables\Columns\IconColumn::make('paid')
                    ->boolean(),
                Tables\Columns\IconColumn::make('completed')
                    ->boolean(),
                Tables\Columns\IconColumn::make('successful')
                    ->boolean(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_new')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_update')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_registered')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_cpaneled')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_installed')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_removed')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_live')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_synced')
                    ->boolean(),
                Tables\Columns\TextColumn::make('call_counter')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_error')
                    ->boolean(),
                Tables\Columns\TextColumn::make('domain_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('server_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partner_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('whmcs_order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_in_whmcs')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

}
