<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Filament\Resources\Form;
use PHPUnit\Event\Code\Test;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                ->label('Nombre')
                ->autofocus()
                ->required()
                ->unique(Student::class, 'name')
                ->placeholder(__('Ingrese Nombre')),
                TextInput::make('email')
                ->label('Correo Electronico')
                ->required()
                ->unique(Student::class, 'email')
                ->placeholder(__('Correo Electronico')),
                TextInput::make('phone_number')
                ->label('Telefono')
                ->tel()
                ->required()
                ->unique(Student::class, 'phone_number')
                ->placeholder(__('Telefono')),

                TextInput::make('address')
                ->label('Direccion')
                ->required()

                ->placeholder(__('Direccion')),

                Select::make('class_id')
                ->relationship('class', 'name')
                ->label('Clase')
                ->reactive(),

                Select::make('section_id')
                ->label('Seleccione Seccion')
                    ->options(function (callable $get) {
                      $classId = $get('class_id');

                    if ($classId) {
                        return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                    }

                })

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                    TextColumn::make('email')
                    ->label('Correo Electronico')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                    TextColumn::make('phone_number')
                    ->label('Telefono')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                    TextColumn::make('address')
                    ->label('Direccion')
                    ->searchable()
                    ->toggleable()
                    ->wrap()
                    ->sortable(),
                    TextColumn::make('class.name')
                    ->label('Clase')
                    ->searchable()
                    ->sortable(),
                    TextColumn::make('section.name')
                    ->label('Seccion')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
