<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convertir un usuario en administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("No se encontró un usuario con el email: {$email}");
            
            $this->info("\nUsuarios disponibles:");
            User::all()->each(function($u) {
                $this->line("{$u->id}. {$u->name} ({$u->email}) - Rol: " . ($u->rol ?? 'usuario'));
            });
            
            return 1;
        }
        
        $user->update(['rol' => 'admin']);
        
        $this->info("✓ Usuario {$user->name} ({$user->email}) es ahora administrador");
        
        return 0;
    }
}

