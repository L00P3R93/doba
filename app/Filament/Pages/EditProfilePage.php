<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions as SchemaActions;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

class EditProfilePage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user';

    protected static bool $shouldRegisterNavigation = false;

    protected string $view = 'filament.pages.edit-profile-page';

    protected static ?string $title = 'Edit Profile';

    protected static ?string $navigationLabel = 'Edit Profile';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    // Two-factor authentication state
    public bool $canManageTwoFactor = false;

    public bool $twoFactorEnabled = false;

    public bool $requiresTwoFactorConfirmation = false;

    public string $qrCodeSvg = '';

    public string $manualSetupKey = '';

    public array $recoveryCodes = [];

    public function mount(): void
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile;

        $this->form->fill([
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'account_no' => $user->account_no,
            'profile_photo' => $user->getFirstMedia('avatars'),
            'bio' => $profile->bio,
            'mpesa_phone' => $profile->mpesa_phone,
            'tier' => $profile->tier,
        ]);

        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            if (Fortify::confirmsTwoFactorAuthentication() && is_null($user->two_factor_confirmed_at)) {
                app(DisableTwoFactorAuthentication::class)($user);
            }

            $this->twoFactorEnabled = $user->hasEnabledTwoFactorAuthentication();
            $this->requiresTwoFactorConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');

            if ($this->twoFactorEnabled) {
                $this->loadRecoveryCodes();
            }
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Account Information')
                            ->icon(Heroicon::OutlinedUser)
                            ->schema([
                                Section::make('Profile Photo')->schema([
                                    SpatieMediaLibraryFileUpload::make('profile_photo')
                                        ->label('Profile Photo')
                                        ->image()
                                        ->collection('avatars')
                                        ->model(fn () => auth()->user())
                                        ->preserveFilenames()
                                        ->imageEditor()
                                        ->openable()
                                        ->downloadable()
                                        ->columnSpanFull()
                                        ->required(false)
                                        ->maxSize(10240) // 10MB max
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif']),
                                ])->columns(1)->compact(),
                                Section::make('Account Details')->schema([
                                    TextInput::make('username')
                                        ->label('Username')
                                        ->prefixIcon(Heroicon::OutlinedUserCircle)
                                        ->prefixIconColor('primary')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique('users', 'username', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                            return $rule->ignore(auth()->id());
                                        }),

                                    TextInput::make('name')
                                        ->label('Full name')
                                        ->prefixIcon(Heroicon::OutlinedUser)
                                        ->prefixIconColor('primary')
                                        ->required(),
                                    TextInput::make('email')
                                        ->label('Email address')
                                        ->email()
                                        ->autocomplete(false)
                                        ->unique('users', 'email', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                            return $rule->ignore(auth()->id());
                                        })
                                        ->prefixIcon(Heroicon::OutlinedAtSymbol)
                                        ->prefixIconColor('primary')
                                        ->validationMessages([
                                            'email' => 'Invalid email address.',
                                            'required' => 'Email address is required.',
                                            'unique' => 'This email address is already in use.',
                                        ])
                                        ->required(),
                                    TextInput::make('phone')
                                        ->label('Phone number')
                                        ->tel()
                                        ->telRegex('/^(?:\+254|254|0)(7\d{8}|1\d{8})$/')
                                        ->unique('users', 'phone', ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                            return $rule->ignore(auth()->id());
                                        })
                                        ->prefixIcon(Heroicon::OutlinedPhone)
                                        ->prefixIconColor('primary')
                                        ->validationMessages([
                                            'unique' => 'This phone number is already in use.',
                                            'required' => 'Phone number is required.',
                                            'regex' => 'Invalid phone number.',
                                        ])
                                        ->required(),

                                    TextInput::make('account_no')
                                        ->label('Account Number')
                                        ->prefixIcon(Heroicon::Hashtag)
                                        ->prefixIconColor('primary')
                                        ->disabled(),
                                ])->columns(2),
                            ]),
                        Tab::make('Profile Details')
                            ->icon(Heroicon::OutlinedDocumentText)
                            ->schema([
                                Section::make('Biography & Subscriptions')
                                    ->schema([
                                        TextInput::make('mpesa_phone')
                                            ->label('M-Pesa Phone')
                                            ->prefixIcon(Heroicon::OutlinedPhone)
                                            ->prefixIconColor('primary')
                                            ->tel()
                                            ->telRegex('/^(?:\+254|254|0)(7\d{8}|1\d{8})$/')
                                            ->helperText('For artist B2C payouts')
                                            ->validationMessages([
                                                'regex' => 'Invalid phone number.',
                                            ]),

                                        TextInput::make('tier')
                                            ->label('Subscription Tier')
                                            ->prefixIcon(Heroicon::OutlinedShoppingBag)
                                            ->prefixIconColor('primary')
                                            ->default('standard')
                                            ->disabled(),
                                        MarkdownEditor::make('bio')
                                            ->label('Biography')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tab::make('Account Security')
                            ->icon(Heroicon::OutlinedLockClosed)
                            ->schema([
                                Section::make('Two-Factor Authentication')
                                    ->description('Add an extra layer of security to your account.')
                                    ->visible(fn () => $this->canManageTwoFactor)
                                    ->schema([
                                        TextEntry::make('two_factor_status')
                                            ->hiddenLabel()
                                            ->state(fn () => $this->twoFactorEnabled
                                                ? 'Two-factor authentication is currently enabled. You will be asked for a secure token during login.'
                                                : 'When enabled, you will be prompted for a secure token from an authenticator app during login.'),

                                        SchemaActions::make([
                                            $this->enableTwoFactorAction(),
                                            $this->disableTwoFactorAction(),
                                        ]),
                                    ])->compact(),

                                Section::make('Recovery Codes')
                                    ->description('Use a recovery code to regain access if you lose your 2FA device.')
                                    ->visible(fn () => $this->canManageTwoFactor && $this->twoFactorEnabled)
                                    ->schema([
                                        SchemaActions::make([
                                            $this->viewRecoveryCodesAction(),
                                            $this->regenerateRecoveryCodesAction(),
                                        ]),
                                    ])->compact(),
                                Section::make('Change Password')
                                    ->description('Leave blank to keep your current password')
                                    ->schema([
                                        TextInput::make('current_password')
                                            ->label('Current Password')
                                            ->prefixIcon(Heroicon::OutlinedLockClosed)
                                            ->prefixIconColor('primary')
                                            ->password()
                                            ->extraInputAttributes([
                                                'autocomplete' => 'new-password',
                                            ])
                                            ->revealable()
                                            ->requiredWith('password')
                                            ->currentPassword(),

                                        TextInput::make('password')
                                            ->label('Password')
                                            ->prefixIcon(Heroicon::OutlinedLockClosed)
                                            ->prefixIconColor('primary')
                                            ->password()
                                            ->revealable()
                                            ->requiredWith('current_password')
                                            ->dehydrated(fn ($state) => filled($state)) // only send if filled
                                            ->rules([
                                                'confirmed',
                                                'regex:/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/',
                                            ])
                                            ->validationMessages([
                                                'regex' => 'Password must be at least 8 characters long, contain at least one uppercase letter, and one special symbol.',
                                                'confirmed' => 'Password confirmation does not match.',
                                                'required' => 'Password is required.',
                                            ]),
                                        TextInput::make('password_confirmation')
                                            ->label('Confirm Password')
                                            ->prefixIcon(Heroicon::OutlinedLockClosed)
                                            ->prefixIconColor('primary')
                                            ->password()
                                            ->revealable()
                                            ->dehydrated(false) // don't send to DB
                                            ->requiredWith('password'),
                                    ])
                                    ->columns(1)->compact(),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->contained(false)
                    ->persistTabInQueryString(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->data;
            $user = auth()->user();

            // Debug logging
            \Log::info('EditProfilePage save called', [
                'user_id' => $user->id,
                'data_keys' => array_keys($data),
                'photo_data' => isset($data['profile_photo']) ? gettype($data['profile_photo']) : 'not_set',
                'photo_value' => $data['profile_photo'] ?? null,
            ]);

            // Validate the data first
            $phoneRegex = '/^(?:\+254|254|0)(7\d{8}|1\d{8})$/';
            $this->validate([
                'data.username' => 'required|string|max:255|unique:users,username,'.$user->id,
                'data.name' => 'required|string|max:255',
                'data.email' => 'required|email|unique:users,email,'.$user->id,
                'data.phone' => ['required', 'string', 'regex:'.$phoneRegex, 'unique:users,phone,'.$user->id],
                'data.bio' => 'nullable|string',
                'data.mpesa_phone' => ['nullable', 'string', 'regex:'.$phoneRegex],
                'data.profile_photo' => 'nullable|array', // SpatieMediaLibraryFileUpload sends array
                'data.profile_photo.*' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:10240', // Validate each file
            ]);

            // Use validated data
            $validated = $this->data;

            // Update user fields
            $user->update([
                'username' => $validated['username'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'account_no' => $data['account_no'],
            ]);

            // Update password if provided
            if (! empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }

            // Update or create profile
            $profile = $user->profile ?? Profile::create([
                'user_id' => $user->id,
            ]);
            $profile->update([
                'bio' => $validated['bio'] ?? $data['bio'],
                'mpesa_phone' => $validated['mpesa_phone'] ?? $data['mpesa_phone'],
                'tier' => $data['tier'],
            ]);

            // Handle profile photo upload manually
            if (isset($data['profile_photo']) && is_array($data['profile_photo'])) {
                // Clear existing media first
                $user->clearMediaCollection('avatars');

                // Process the uploaded file
                foreach ($data['profile_photo'] as $tempFileData) {
                    if (isset($tempFileData['Livewire\\Features\\SupportFileUploads\\TemporaryUploadedFile'])) {
                        $tempPath = $tempFileData['Livewire\\Features\\SupportFileUploads\\TemporaryUploadedFile'];
                        if (file_exists($tempPath)) {
                            $user->addMedia($tempPath)
                                ->toMediaCollection('avatars');
                        }
                    }
                }
            }

            Notification::make()
                ->success()
                ->title('Profile updated')
                ->body('Your profile has been successfully updated.')
                ->send();

            // Reset password fields after successful save
            $this->form->fill([
                ...$data,
                'current_password' => null,
                'password' => null,
                'password_confirmation' => null,
            ]);
        } catch (Halt $exception) {
            return;
        } catch (\Exception $e) {
            \Log::error('EditProfilePage save error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('There was an error updating your profile. Please try again.')
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [];
    }

    // Two-factor authentication actions
    protected function enableTwoFactorAction(): Action
    {
        return Action::make('enableTwoFactor')
            ->label('Enable 2FA')
            ->icon(Heroicon::OutlinedShieldCheck)
            ->color('primary')
            ->visible(fn () => ! $this->twoFactorEnabled)
            ->modalHeading('Enable two-factor authentication')
            ->modalDescription('Scan the QR code or enter the setup key in your authenticator app.')
            ->modalSubmitActionLabel($this->requiresTwoFactorConfirmation ? 'Continue' : 'Enable')
            ->mountUsing(function () {
                app(EnableTwoFactorAuthentication::class)(auth()->user());
                $this->loadTwoFactorSetupData();
            })
            ->modalContent(fn () => view('filament.pages.partials.⚡two-factor-qr', [
                'qrCodeSvg' => $this->qrCodeSvg,
                'manualSetupKey' => $this->manualSetupKey,
            ]))
            ->action(function () {
                if ($this->requiresTwoFactorConfirmation) {
                    $this->qrCodeSvg = '';
                    $this->manualSetupKey = '';
                    $this->replaceMountedAction('confirmTwoFactor');

                    return;
                }

                $this->twoFactorEnabled = true;
                $this->loadRecoveryCodes();

                Notification::make()
                    ->success()
                    ->title('Two-factor authentication enabled')
                    ->send();
            });
    }

    protected function confirmTwoFactorAction(): Action
    {
        return Action::make('confirmTwoFactor')
            ->label('Verify')
            ->modalHeading('Verify authentication code')
            ->modalDescription('Enter the 6-digit code from your authenticator app.')
            ->schema([
                TextInput::make('code')
                    ->label('Authentication Code')
                    ->required()
                    ->rule('digits:6')
                    ->autocomplete('one-time-code')
                    ->inputMode('numeric')
                    ->placeholder('123456'),
            ])
            ->action(function (array $data, ConfirmTwoFactorAuthentication $confirmTwoFactorAuth) {
                $confirmTwoFactorAuth(auth()->user(), (string) $data['code']);

                $this->twoFactorEnabled = true;
                $this->loadRecoveryCodes();

                Notification::make()
                    ->success()
                    ->title('Two-factor authentication enabled')
                    ->send();
            });
    }

    protected function disableTwoFactorAction(): Action
    {
        return Action::make('disableTwoFactor')
            ->label('Disable 2FA')
            ->icon(Heroicon::OutlinedShieldExclamation)
            ->color('danger')
            ->visible(fn () => $this->twoFactorEnabled)
            ->requiresConfirmation()
            ->modalHeading('Disable two-factor authentication')
            ->modalDescription('Are you sure you want to disable two-factor authentication?')
            ->action(function (DisableTwoFactorAuthentication $disableTwoFactorAuth) {
                $disableTwoFactorAuth(auth()->user());
                $this->twoFactorEnabled = false;
                $this->recoveryCodes = [];

                Notification::make()
                    ->success()
                    ->title('Two-factor authentication disabled')
                    ->send();
            });
    }

    protected function viewRecoveryCodesAction(): Action
    {
        return Action::make('viewRecoveryCodes')
            ->label('View recovery codes')
            ->icon(Heroicon::OutlinedEye)
            ->modalHeading('2FA recovery codes')
            ->modalDescription('Store these in a secure password manager. Each code can only be used once.')
            ->modalContent(fn () => view('filament.pages.partials.⚡recovery-codes', [
                'codes' => $this->recoveryCodes,
            ]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close');
    }

    protected function regenerateRecoveryCodesAction(): Action
    {
        return Action::make('regenerateRecoveryCodes')
            ->label('Regenerate codes')
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading('Regenerate recovery codes')
            ->modalDescription('Your existing recovery codes will no longer work.')
            ->action(function (GenerateNewRecoveryCodes $generateNewRecoveryCodes) {
                $generateNewRecoveryCodes(auth()->user());
                $this->loadRecoveryCodes();

                Notification::make()
                    ->success()
                    ->title('Recovery codes regenerated')
                    ->send();
            });
    }

    protected function loadTwoFactorSetupData(): void
    {
        $user = auth()->user()?->fresh();

        try {
            if (! $user || ! $user->two_factor_secret) {
                throw new \Exception('Two-factor setup secret is not available.');
            }

            $this->qrCodeSvg = $user->twoFactorQrCodeSvg();
            $this->manualSetupKey = method_exists($user, 'twoFactorSecretKey')
                ? $user->twoFactorSecretKey()
                : decrypt($user->two_factor_secret);
        } catch (\Exception) {
            $this->qrCodeSvg = '';
            $this->manualSetupKey = '';

            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Failed to load 2FA setup data')
                ->send();
        }
    }

    protected function loadRecoveryCodes(): void
    {
        $user = auth()->user();
        if ($user->hasEnabledTwoFactorAuthentication() && $user->two_factor_recovery_codes) {
            try {
                $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
            } catch (\Exception) {
                $this->recoveryCodes = [];
            }
        } else {
            $this->recoveryCodes = [];
        }
    }
}
