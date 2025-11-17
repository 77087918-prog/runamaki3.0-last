<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'puntos_runa',
        'reputacion',
        'nivel',
        'estado',
        'rol',
        'ubicacion',
        'ultima_conexion',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'puntos_runa' => 'integer',
        'reputacion' => 'decimal:2',
        'ultima_conexion' => 'datetime',
    ];

    /**
     * Relación: usuario tiene muchas habilidades
     */
    public function habilidades(): HasMany
    {
        return $this->hasMany(Habilidad::class, 'usuario_id');
    }

    /**
     * Trueques donde el usuario ofrece
     */
    public function truequesOfrecidos(): HasMany
    {
        return $this->hasMany(Trueque::class, 'usuario_ofrece_id');
    }

    /**
     * Trueques donde el usuario recibe
     */
    public function truequesRecibidos(): HasMany
    {
        return $this->hasMany(Trueque::class, 'usuario_recibe_id');
    }

    /**
     * Todos los trueques del usuario
     */
    public function trueques()
    {
        return $this->truequesOfrecidos->merge($this->truequesRecibidos);
    }

    /**
     * Mensajes enviados en trueques
     */
    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'remitente_id');
    }

    /**
     * Valoraciones dadas por el usuario
     */
    public function valoracionesDadas(): HasMany
    {
        return $this->hasMany(Valoracion::class, 'evaluador_id');
    }

    /**
     * Valoraciones recibidas por el usuario
     */
    public function valoracionesRecibidas(): HasMany
    {
        return $this->hasMany(Valoracion::class, 'evaluado_id');
    }

    /**
     * Logros obtenidos por el usuario
     */
    public function logros(): BelongsToMany
    {
        return $this->belongsToMany(Logro::class, 'usuarios_logros', 'usuario_id', 'logro_id')
            ->withTimestamps();
    }

    /**
     * Denuncias realizadas por el usuario
     */
    public function denunciasRealizadas(): HasMany
    {
        return $this->hasMany(Denuncia::class, 'denunciante_id');
    }

    /**
     * Denuncias recibidas por el usuario
     */
    public function denunciasRecibidas(): HasMany
    {
        return $this->hasMany(Denuncia::class, 'denunciado_id');
    }

    /**
     * Transacciones de puntos del usuario
     */
    public function transaccionesPuntos(): HasMany
    {
        return $this->hasMany(TransaccionPunto::class, 'usuario_id');
    }

    /**
     * Mensajes del chat con IA
     */
    public function chatMensajes(): HasMany
    {
        return $this->hasMany(ChatMensaje::class);
    }

    public function isAdmin(): bool
    {
        return ($this->rol ?? 'usuario') === 'admin';
    }

    public function isActivo(): bool
    {
        return ($this->estado ?? 'activo') === 'activo';
    }

    /**
     * Obtener el nivel del usuario basado en puntos Runa
     */
    public function getNivelAttribute(): string
    {
        $puntos = $this->puntos_runa ?? 0;
        
        if ($puntos >= 300) return 'Platino';
        if ($puntos >= 200) return 'Oro';
        if ($puntos >= 100) return 'Plata';
        return 'Bronce';
    }

    /**
     * Obtener emoji del nivel
     */
    public function getNivelEmojiAttribute(): string
    {
        return match($this->nivel) {
            'Platino' => '💎',
            'Oro' => '🥇',
            'Plata' => '🥈',
            default => '🥉'
        };
    }

    /**
     * Obtener puntos necesarios para siguiente nivel
     */
    public function getPuntosParaSiguienteNivelAttribute(): int
    {
        $puntos = $this->puntos_runa ?? 0;
        
        if ($puntos >= 300) return 0; // Nivel máximo
        if ($puntos >= 200) return 300 - $puntos;
        if ($puntos >= 100) return 200 - $puntos;
        return 100 - $puntos;
    }

    /**
     * Obtener progreso al siguiente nivel (0-100)
     */
    public function getProgresoNivelAttribute(): float
    {
        $puntos = $this->puntos_runa ?? 0;
        
        if ($puntos >= 300) return 100;
        if ($puntos >= 200) return (($puntos - 200) / 100) * 100;
        if ($puntos >= 100) return (($puntos - 100) / 100) * 100;
        return ($puntos / 100) * 100;
    }

    /**
     * Obtener URL del avatar con fallback a DiceBear
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar) && filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }
        
        // DiceBear API - estilo adventurer-neutral
        $seed = urlencode($this->email ?? $this->name ?? 'default');
        return "https://api.dicebear.com/7.x/adventurer-neutral/svg?seed={$seed}";
    }

    /**
     * Obtener iniciales del nombre para avatar fallback
     */
    public function getInicialesAttribute(): string
    {
        $words = explode(' ', $this->name ?? 'U');
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name ?? 'U', 0, 2));
    }
}
