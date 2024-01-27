<?php

namespace App\Livewire;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use Filament\Forms\Components\Card;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Form extends Component

{

    use InteractsWithForms;

    protected function getFormSchema(): array
    {
        return Card::make()->schema([
        Forms\Components\TextInput::make('name')
            ->required()
            ->maxLength(255),
        Forms\Components\Toggle::make('is_admin')
            ->required(),
        Forms\Components\TextInput::make('email')
            ->email()
            ->required()
            ->maxLength(255),
        Forms\Components\TextInput::make('password')
            ->password()
            ->maxLength(255)
            ->dehydrateStateUsing(static fn (null|string $state): null|string =>
            filled($state) ? Hash::make($state): null,
            )->required(static fn (Page $livewire): bool =>
                $livewire instanceof CreateUser,
            )->dehydrated(static fn (null|string $state): bool =>
            filled($state),
            )->label(static fn (Page $livewire): string =>
            ($livewire instanceof EditUser) ? 'New Password' : 'Password'
            ),
        Forms\Components\CheckboxList::make('roles')
            ->relationship('roles', 'name')
            ->columns(2)
            ->helperText('Only Choose One!')
            ->required()
    ]);
    }
    public function render()
    {
        return view('livewire.form');
    }
}
