<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function cidadesAtendidas(): BelongsToMany
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
     * Busca diaristas limitando a 6 por página
     *
     * @param integer $codigoIbge
     * @return Collection
     */
    static public function diaristaDisponivelCidade(int $codigoIbge): Collection
    {
        return User::diaristaCidade($codigoIbge)->limit(6)->get();
    }

    /**
     * Retorna a quantidade de diaristas por código ibge
     *
     * @param integer $codigoIbge
     * @return integer
     */
    static public function diaristaDiponivelCidadeTotal(int $codigoIbge): int
    {
        return User::diaristaCidade($codigoIbge)->count();
    }

}
