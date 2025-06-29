<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('product.name'))
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('product_number')
                    ->label(__('product.product_number'))
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label(__('product.price'))
                    ->required()
                    ->columnSpanFull()
                    ->numeric()
                    ->prefix('€'),
                RichEditor::make('description')
                    ->label(__('product.description'))
                    ->columnSpanFull(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                    ->label(__('product.image'))
                    ->collection('product-images')
                    ->image()
                    ->columnSpanFull()
                    ->preserveFilenames()
                    ->maxFiles(1),
                Select::make('categories')
                    ->label(__('product.categories'))
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->options(function () {
                        return Category::active()
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('product.is_active'))
                    ->required()
                    ->columnSpanFull()
                    ->reactive(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label(__('product.thumbnail'))
                    ->getStateUsing(fn($record) => $record->getFirstMediaUrl('product-images'))
                    ->square()
                    ->height(50)
                    ->width(50),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('product.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_number')
                    ->label(__('product.product_number'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('product.price'))
                    ->money('eur')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('categories.name')
                    ->label(__('product.categories'))
                    ->badge()
                    ->separator(', ')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('product.is_active'))
                    ->boolean()
                    ->toggleable()
                    ->action(function (Product $product) {
                        $product->toggle();
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('product.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('product.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return __('navigation.product');
    }

    public static function getPluralLabel(): ?string
    {
        return __('navigation.products');
    }
}
