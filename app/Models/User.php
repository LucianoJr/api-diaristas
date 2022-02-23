<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define a relação com as cidades atendidas pela diarista
     */
    public function cidadesAtendidas()
    {
        return $this->belongsToMany(Cidade::class, 'cidade_diarista');
    }
    /**
     * Escopo para filtrar diaristas
     */
    public function scopeDiarista(Builder $query): Builder
    {
        return $query->where('tipo_usuario', '=', 2);
    }

    public function scopeDiaristaCidade(Builder $query, int $codigoIbge)
    {
        return $query->diarista()
                     ->whereHas('cidadesAtendidas', function($q) use ($codigoIbge) {
                        $q->where('codigo_ibge', '=', $codigoIbge);
                     });
    }

    /**
     * Busca 6 diaristas por código ibge
     */
    static public function diaristaDisponivelCidade(int $codigoIbge)
    {
        return User::diaristaCidade($codigoIbge)->limit(6)->get();
    }

    /**
     * Retorna a quantidade de diaristas por código
     */
    static public function diaristaDiponivelCidadeTotal(int $codigoIbge): int
    {
        return User::diaristaCidade($codigoIbge)->count();
    }

}
