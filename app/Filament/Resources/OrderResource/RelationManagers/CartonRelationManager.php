<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CartonRelationManager extends RelationManager
{
    protected static string $relationship = 'cartons';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Delivery Order')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->delivery_order)
                    ->searchable(),
                Forms\Components\Select::make('product_id')
                    ->label('Product Order')
                    ->required()
                    ->options(Product::all()->pluck('product_name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $this->updateItemsPerCarton($state, $set)),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('items_per_carton')
                    ->label('Items per Carton')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function updateItemsPerCarton($productId, callable $set)
    {
        $product = Product::find($productId);
        if ($product) {
            $set('items_per_carton', $product->items_per_carton);
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order_id')
            ->columns([
                Tables\Columns\TextColumn::make('order.delivery_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.product_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_per_carton')
                    ->numeric()
                    ->sortable(),
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Action::make('printPdf')
                    ->icon('heroicon-o-printer')
                    ->url(fn (RelationManager $livewire) => route('packing-list.generate', ['orderId' => $livewire->ownerRecord->id]))
                    ->openUrlInNewTab()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
