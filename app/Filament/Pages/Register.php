<?php

namespace App\Filament\Pages;

use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use App\Models\Author;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextArea;
use App\Models\User;
use Filament\Http\Responses\Auth\RegistrationResponse;
use Illuminate\Support\Facades\Hash;
use Filament\Pages\Page;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Auth\Events\Registered;
use Filament\Notifications\Notification;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('username')
                    ->label('Username')
                    ->required()
                    ->maxLength(255)
                    ->unique(Author::class),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(User::class),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('passwordConfirmation'),
                TextInput::make('passwordConfirmation')
                    ->label('Konfirmasi Password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->dehydrated(false),
                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('avatars'),
                TextArea::make('bio')
                    ->label('Bio')
                    ->required()
                    ->maxLength(1000)
                    ->rows(3),
            ])->statePath('data');
    }

    public function register(): ?RegistrationResponse
    {
        try {
          $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title('Terlalu banyak percobaan registrasi')
                ->body('Silakan coba lagi dalam ' . $exception->secondsUntilAvailable . ' detik.')
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'author',
        ]);

            $author = Author::create([
                'user_id' => $user->id,
                'username' => $data['username'],
                'avatar' => $data['avatar'],
                'bio' => $data['bio'],
            ]);

            event(new Registered($user));

            $this->form->fill();

            Notification::make()
                ->title('Registrasi Berhasil')
                ->success()
                ->send();

            return app(RegistrationResponse::class);
    }
    
}
