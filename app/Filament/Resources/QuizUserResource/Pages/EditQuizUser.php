<?php

namespace App\Filament\Resources\QuizUserResource\Pages;

use App\Filament\Resources\QuizUserResource;
use App\Mail\QuizParticipantPasswordMail;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EditQuizUser extends EditRecord
{
    protected static string $resource = QuizUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('changePassword')
                ->label('Change Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->modalHeading(fn(User $record) => "Change Password: {$record->name}")
                ->modalDescription('Enter a new password or leave blank to auto-generate. It can be emailed to the participant.')
                ->modalIcon('heroicon-o-key')
                ->modalWidth('md')
                ->form([
                    Forms\Components\TextInput::make('email')
                        ->label('Participant Email')
                        ->email()
                        ->default(fn(User $record) => $record->email)
                        ->helperText('Password notification will be sent to this email address.'),

                    Forms\Components\TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->minLength(6)
                        ->placeholder('Leave blank to auto-generate')
                        ->helperText('Min 6 characters. If left blank, a secure password will be generated automatically.'),

                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->label('Confirm New Password')
                        ->password()
                        ->revealable()
                        ->same('new_password')
                        ->visible(fn(Forms\Get $get): bool => filled($get('new_password'))),

                    Forms\Components\Toggle::make('send_email')
                        ->label('Email new password to participant')
                        ->default(true)
                        ->helperText('Sends an email formatted in the website theme with the new login credentials.'),
                ])
                ->action(function (User $record, array $data) {
                    $plainPassword = filled($data['new_password'] ?? null)
                        ? trim($data['new_password'])
                        : (Str::lower(Str::random(4)) . rand(1000, 9999));

                    if (!empty($data['email']) && $data['email'] !== $record->email) {
                        $record->email = trim($data['email']);
                    }

                    $record->password = Hash::make($plainPassword);
                    $record->save();

                    $emailSent = false;
                    if (!empty($data['send_email']) && !empty($record->email)) {
                        try {
                            Mail::to($record->email)->send(new QuizParticipantPasswordMail($record, $plainPassword, url('/thibyan'), 'admin_update'));
                            $emailSent = true;
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Password updated but email failed')
                                ->body("Password updated to '{$plainPassword}'. Email could not be sent: " . $e->getMessage())
                                ->warning()
                                ->send();
                            return;
                        }
                    }

                    Notification::make()
                        ->title('Password updated successfully')
                        ->body($emailSent
                            ? "Password for {$record->name} changed to '{$plainPassword}' and emailed to {$record->email}."
                            : "Password for {$record->name} changed to '{$plainPassword}'.")
                        ->success()
                        ->send();
                }),

            Actions\Action::make('emailPassword')
                ->label('Email New Password')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading(fn(User $record) => "Generate & Email Password: {$record->name}")
                ->modalDescription(fn(User $record) => !empty($record->email)
                    ? "A new secure password will be generated and emailed directly to {$record->email}."
                    : "This participant does not have an email registered. Please use 'Change Password' to provide an email first.")
                ->modalSubmitActionLabel('Generate & Send Email')
                ->visible(fn(User $record) => !empty($record->email))
                ->action(function (User $record) {
                    $plainPassword = Str::lower(Str::random(4)) . rand(1000, 9999);
                    $record->update([
                        'password' => Hash::make($plainPassword),
                    ]);

                    try {
                        Mail::to($record->email)->send(new QuizParticipantPasswordMail($record, $plainPassword, url('/thibyan'), 'admin_update'));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Failed to send email')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                        return;
                    }

                    Notification::make()
                        ->title('Password emailed successfully')
                        ->body("A new password ('{$plainPassword}') has been generated and emailed to {$record->email}.")
                        ->success()
                        ->send();
                }),

            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
