<?php

namespace App\Console\Commands;

use App\Enums\IdentifierType;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {login} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create empty user with password. Or change password on exists user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (!$this->argument('login') || !$this->argument('password')) {
            throw new \Exception('Login and password is required');
        }

        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        $user = $userRepository->findOrCreateByIdentifier(IdentifierType::LOGIN, $this->argument('login'));

        $user->password = Hash::make($this->argument('password'));
        $user->save();
    }
}
