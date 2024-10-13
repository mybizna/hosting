<?php

namespace Modules\Hosting\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Base\Filament\Resources\BaseResource;
use Modules\Hosting\Filament\Resources\PackageResource\Pages;
use Modules\Hosting\Models\Package;

class PackageResource extends BaseResource
{
    protected static ?string $model = Package::class;

    protected static ?string $slug = 'hosting/package';

    protected static ?string $navigationGroup = 'Hosting';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('unique_name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('plan')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                Forms\Components\TextInput::make('setup_fee')
                    ->numeric()
                    ->default(null),
                Forms\Components\TextInput::make('no_of_days')
                    ->numeric()
                    ->default(null),
                Forms\Components\Toggle::make('is_default'),
                Forms\Components\Toggle::make('published'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('setup_fee')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_of_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean(),
                Tables\Columns\IconColumn::make('published')
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

    public static function getPages(): array
    {

        return [
            'index' => Pages\Listing::route('/'),
            'create' => Pages\Creating::route('/create'),
            'edit' => Pages\Editing::route('/{record}/edit'),
        ];
    }
}
