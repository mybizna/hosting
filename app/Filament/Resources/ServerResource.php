<?php

namespace Modules\Hosting\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Base\Filament\Resources\BaseResource;
use Modules\Hosting\Filament\Resources\ServerResource\Pages;
use Modules\Hosting\Models\Server;

class ServerResource extends BaseResource
{
    protected static ?string $model = Server::class;

    protected static ?string $slug = 'hosting/server';

    protected static ?string $navigationGroup = 'Hosting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('domain')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('auth_type')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('total_accounts')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('limiting')
                    ->numeric()
                    ->default(null),
                Forms\Components\Toggle::make('is_full'),
                Forms\Components\TextInput::make('nameserver_1')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nameserver_2')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nameserver_3')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('nameserver_4')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Toggle::make('published'),
                Forms\Components\TextInput::make('ip_adsdress')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('auth_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_accounts')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('limiting')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_full')
                    ->boolean(),
                Tables\Columns\TextColumn::make('nameserver_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nameserver_2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nameserver_3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nameserver_4')
                    ->searchable(),
                Tables\Columns\IconColumn::make('published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('ip_adsdress')
                    ->searchable(),
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

    public static function getPages(): array
    {

        return [
            'index' => Pages\Listing::route('/'),
            'create' => Pages\Creating::route('/create'),
            'edit' => Pages\Editing::route('/{record}/edit'),
        ];
    }
}
