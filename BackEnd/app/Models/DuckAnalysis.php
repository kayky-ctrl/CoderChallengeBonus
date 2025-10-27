<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuckAnalysis extends Model
{
    /**
     * O nome da tabela associada com o model.
     * Laravel assumiria 'duck_analysis' (singular), mas a migration cria 'duck_analyses' (plural).
     * @var string
     */
    protected $table = 'duck_analyses';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'operational_cost',
        'military_power_needed',
        'risk_level',
        'scientific_value',
        'capture_priority',
        'analysis_notes',
        'primordial_duck_id' // A chave estrangeira
    ];

    /**
     * Os atributos que devem ser ocultados para serialização.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'primordial_duck_id',
        'id' // Opcional, às vezes útil ocultar
    ];

    /**
     * Os atributos que devem ter tipos nativos definidos.
     * Ajuda na formatação automática.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'operational_cost' => 'decimal:2', // Converte para float com 2 casas decimais
        'scientific_value' => 'integer',
        'capture_priority' => 'integer',
    ];

    /**
     * Obtém o Pato Primordial ao qual esta análise pertence.
     */
    public function primordialDuck(): BelongsTo
    {
        // Define o relacionamento inverso: Uma Análise pertence a um Pato
        // Argumentos: Classe relacionada, chave estrangeira nesta tabela, chave primária na tabela relacionada
        return $this->belongsTo(PrimordialDuck::class, 'primordial_duck_id', 'id');
    }
}
