<?php

namespace App\Filament\Resources;

use App\Exports\EmployeesExport;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return ucfirst(__('common.employee'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('profile')
                    ->label(ucfirst(__('common.profile_photo')))
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->directory('employees')
                    ->imageCropAspectRatio('1:1')
                    ->imagePreviewHeight('100')
                    ->disk('public')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->alignCenter(),
                Forms\Components\Select::make('department_id')
                    ->label(ucfirst(__('common.department')))
                    ->relationship('department', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->createOptionForm(JobTitleResource::getFormComponents())
                    ->createOptionAction(fn ($action) => $action->modalWidth('sm'))
                    ->required(),
                Forms\Components\Select::make('job_title_id')
                    ->label(ucfirst(__('common.job_title')))
                    ->relationship('jobTitle', 'name')
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->createOptionForm(JobTitleResource::getFormComponents())
                    ->createOptionAction(fn ($action) => $action->modalWidth('sm'))
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label(ucfirst(__('common.name')))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('registration_number')
                    ->label(ucfirst(__('common.registration_number')))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile')
                    ->label(ucfirst(__('common.profile_photo')))
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('name')
                    ->label(ucfirst(__('common.name')))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_number')
                    ->label(ucfirst(__('common.registration_number')))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobTitle.name')
                    ->label(ucfirst(__('common.job_title')))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label(ucfirst(__('common.department')))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uid')
                    ->label('QR Code Link')
                    ->icon('heroicon-o-qr-code')
                    ->copyable()
                    ->copyableState(fn (Employee $record): string => url('/employee', $record->uid)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(ucfirst(__('common.created_at')))
                    ->dateTime('d M, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(ucfirst(__('common.updated_at')))
                    ->dateTime('d M, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(ucfirst(__('common.deleted_at')))
                    ->dateTime('d M, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('export')
                ->label(ucfirst(__('common.export')))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new EmployeesExport, 'employees.xlsx');
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ManageEmployees::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
