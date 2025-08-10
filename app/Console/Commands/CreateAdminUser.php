<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

final class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create
                            {--name= : Full name of the admin user}
                            {--email= : Email address for the admin user}
                            {--phone= : Phone number for the admin user}
                            {--password= : Password for the admin user}
                            {--language=en : Preferred language (en, tw)}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user for the Ayeyie Poultry Feed system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🐔 Ayeyie Poultry Feed - Admin User Creation');
        $this->info('==========================================');

        // Collect user information
        $userData = $this->collectUserData();

        if (!$userData) {
            $this->error('❌ Admin user creation cancelled.');
            return Command::FAILURE;
        }

        // Validate the collected data
        $validator = $this->validateUserData($userData);
        
        if ($validator->fails()) {
            $this->error('❌ Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->line("   • $error");
            }
            return Command::FAILURE;
        }

        // Check if user already exists
        if ($this->userExists($userData)) {
            return Command::FAILURE;
        }

        // Show summary and confirm creation
        if (!$this->confirmCreation($userData)) {
            $this->info('❌ Admin user creation cancelled.');
            return Command::FAILURE;
        }

        // Create the admin user
        try {
            $user = $this->createAdminUser($userData);
            $this->displaySuccessMessage($user);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error creating admin user: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }

    /**
     * Collect user data from options or prompts.
     *
     * @return array<string, string>|null
     */
    private function collectUserData(): ?array
    {
        return [
            'name' => $this->option('name') ?: $this->ask('👤 Full name'),
            'email' => $this->option('email') ?: $this->ask('📧 Email address'),
            'phone' => $this->option('phone') ?: $this->ask('📱 Phone number'),
            'password' => $this->option('password') ?: $this->secret('🔒 Password'),
            'language' => $this->option('language') ?: $this->choice(
                '🌐 Preferred language',
                ['en' => 'English', 'tw' => 'Twi'],
                'en'
            ),
        ];
    }

    /**
     * Validate the user data.
     *
     * @param array<string, string> $userData
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateUserData(array $userData): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($userData, [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:10', 'max:20', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8'],
            'language' => ['required', 'in:en,tw'],
        ], [
            'name.required' => 'Full name is required.',
            'name.min' => 'Full name must be at least 2 characters.',
            'name.max' => 'Full name cannot exceed 100 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'language.in' => 'Language must be either "en" (English) or "tw" (Twi).',
        ]);
    }

    /**
     * Check if user already exists.
     *
     * @param array<string, string> $userData
     */
    private function userExists(array $userData): bool
    {
        $existingUser = User::where('email', $userData['email'])
            ->orWhere('phone', $userData['phone'])
            ->first();

        if ($existingUser) {
            $this->error('❌ A user with this email or phone number already exists.');
            $this->line("   📧 Email: {$existingUser->email}");
            $this->line("   📱 Phone: {$existingUser->phone}");
            $this->line("   👤 Name: {$existingUser->name}");
            $this->line("   🎭 Role: {$existingUser->role}");
            return true;
        }

        return false;
    }

    /**
     * Show summary and confirm creation.
     *
     * @param array<string, string> $userData
     */
    private function confirmCreation(array $userData): bool
    {
        if ($this->option('force')) {
            return true;
        }

        $this->info('📋 Admin User Summary:');
        $this->line("   👤 Name: {$userData['name']}");
        $this->line("   📧 Email: {$userData['email']}");
        $this->line("   📱 Phone: {$userData['phone']}");
        $this->line("   🌐 Language: " . ($userData['language'] === 'en' ? 'English' : 'Twi'));
        $this->line("   🎭 Role: admin");
        $this->newLine();

        return $this->confirm('✅ Create this admin user?', true);
    }

    /**
     * Create the admin user.
     *
     * @param array<string, string> $userData
     */
    private function createAdminUser(array $userData): User
    {
        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'password' => Hash::make($userData['password']),
            'role' => 'admin',
            'language' => $userData['language'],
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Display success message with user details.
     */
    private function displaySuccessMessage(User $user): void
    {
        $this->newLine();
        $this->info('🎉 Admin user created successfully!');
        $this->info('================================');
        $this->line("   🆔 User ID: {$user->user_id}");
        $this->line("   👤 Name: {$user->name}");
        $this->line("   📧 Email: {$user->email}");
        $this->line("   📱 Phone: {$user->phone}");
        $this->line("   🌐 Language: " . ($user->language === 'en' ? 'English' : 'Twi'));
        $this->line("   🎭 Role: {$user->role}");
        $this->line("   📅 Created: {$user->created_at?->format('Y-m-d H:i:s')}");
        $this->newLine();
        $this->info('✅ The admin user can now log in to the system!');
    }
}
