<?php

namespace App\Filament\App\Resources;

use stdClass;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\App\Resources\ProductResource\Pages;
use App\Filament\App\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('weight_per_unit')
                    ->label('Weight (kg)')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('volume_per_unit')
                    ->label('Volume (m³)')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('dimension')
                    ->label('Dimensions (L x W x H)')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Split input string by "x" and trim extra spaces
                        $dimensions = explode('x', $state);
                        if (count($dimensions) === 3) {
                            $length = (float)(trim($dimensions[0])) / 100;
                            $width = (float)(trim($dimensions[1])) / 100;
                            $height = (float)(trim($dimensions[2])) / 100;
                            // Calculate the volume
                            $volume = $length * $width * $height;

                            $formattedVolume = number_format($volume, 3, '.', '');

                            // Set the volume
                            $set('volume_per_unit', $formattedVolume);
                        } else {
                            // Handle invalid input
                            $set('volume_per_unit', 0);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No')->state(
                    static function (HasTable $livewire, stdClass $rowLoop): string {
                        return (string) (
                            $rowLoop->iteration +
                            ($livewire->getTableRecordsPerPage() * (
                                $livewire->getTablePage() - 1
                            ))
                        );
                    }
                ),
                Tables\Columns\TextColumn::make('product_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight_per_unit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('volume_per_unit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dimension')
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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
}
