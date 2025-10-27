<?php

namespace App\Services;

use App\Models\PrimordialDuck;
use App\Models\AttackStrategy;
use Illuminate\Support\Arr; 
use Illuminate\Support\Facades\Log;
use Throwable;

class TacticalService
{
    public function getTacticalPlan(PrimordialDuck $duck): array
    {
        
        $weaknesses = $duck->DuckWeaknesses()->get(['weaknesses.name', 'weaknesses.description']);


        $allStrategies = AttackStrategy::all();
        Log::info("TacticalPlan: Found {$allStrategies->count()} total strategies.");

        
        $recommendedStrategies = $allStrategies->filter(function (AttackStrategy $strategy) use ($duck) {
            Log::debug("TacticalPlan: Evaluating Strategy ID: {$strategy->id} ('{$strategy->name}')");

            
            $logic = $strategy->trigger_logic;

            
            if (!is_array($logic)) {
                Log::warning("TacticalPlan: Trigger logic is not an array for Strategy ID {$strategy->id}. Check \$casts in AttackStrategy.php.");
                return false;
            }

            
            if (empty($logic)) {
                Log::debug("TacticalPlan: Strategy ID {$strategy->id} has no trigger_logic. Including.");
                return true;
            }

            
            if (!Arr::has($logic, ['field', 'operator', 'value'])) {
                Log::warning("TacticalPlan: Invalid trigger_logic structure for Strategy ID {$strategy->id}: " . json_encode($logic));
                return false;
            }

            $field = $logic['field'];
            $operator = $logic['operator'];
            $value = $logic['value'];

            
            $duckValue = $duck->getAttribute($field);

            
            if ($duckValue === null && !$duck->hasAttribute($field)) {
                Log::warning("TacticalPlan: Field '{$field}' not found or is null on Duck (ID: {$duck->id}) for Strategy ID {$strategy->id}.");
                return false;
            }

            Log::debug("TacticalPlan: Comparing Strategy ID {$strategy->id}: Duck Field '{$field}' ({$duckValue}) {$operator} {$value}");

            
            try {
                switch ($operator) {
                    case '>': return $duckValue > $value;
                    case '<': return $duckValue < $value;
                    case '>=': return $duckValue >= $value;
                    case '<=': return $duckValue <= $value;
                    case '=':
                    case '==': return $duckValue == $value; 
                    case '!=': return $duckValue != $value;
                    case 'in':
                        if (!is_array($value)) {
                            Log::warning("TacticalPlan: Value for 'in' operator must be an array in Strategy ID {$strategy->id}.");
                            return false;
                        }
                        return in_array($duckValue, $value);
                    
                    case 'contains':
                        if (!is_array($duckValue)) {
                            Log::warning("TacticalPlan: Field '{$field}' must be an array for 'contains' operator in Strategy ID {$strategy->id}.");
                            return false;
                        }
                        return in_array($value, $duckValue); 
                    default:
                        Log::warning("TacticalPlan: Unsupported operator '{$operator}' in Strategy ID {$strategy->id}.");
                        return false;
                }
            } catch (Throwable $e) { 
                Log::error("TacticalPlan: Error during comparison for Strategy ID {$strategy->id}: " . $e->getMessage());
                return false;
            }

        })->map(function (AttackStrategy $strategy) {
            
            return ['id' => $strategy->id, 'name' => $strategy->name, 'description' => $strategy->description];
        })->values(); 

        Log::info("TacticalPlan: {$recommendedStrategies->count()} recommended strategies found.");

        
        return [
            
            'known_weaknesses' => $weaknesses,
            'recommended_strategies' => $recommendedStrategies,
        ];
    }
}
