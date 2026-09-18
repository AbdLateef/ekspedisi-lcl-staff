<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create 
                            {name? : Name of the user} 
                            {email? : Email address} 
                            {password? : Password for the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new staff user account via terminal';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('👤 Creating a new Staff User...');

        $name = $this->argument('name') ?: $this->ask('Enter User Full Name');
        $email = $this->argument('email') ?: $this->ask('Enter User Email');

        // Validate Email
        $validator = Validator::make(['email' => $email], [
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        if ($validator->fails()) {
            $this->error('❌ Validation Error: ' . implode(', ', $validator->errors()->all()));
            return Command::FAILURE;
        }

        $password = $this->argument('password') ?: $this->secret('Enter Password (input will be hidden)');

        if (strlen($password) < 6) {
            $this->error('❌ Password must be at least 6 characters long.');
            return Command::FAILURE;
        }

        $user = User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->newLine();
        $this->info('✅ User created successfully!');
        $this->table(
            ['ID', 'Name', 'Email', 'Verified At'],
            [
                [$user->id, $user->name, $user->email, (string) ($user->email_verified_at ?? now())],
            ]
        );

        return Command::SUCCESS;
    }
}
