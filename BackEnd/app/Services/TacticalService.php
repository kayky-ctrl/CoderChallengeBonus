<?php

namespace App\Services;

use App\Models\PrimordialDuck;
use App\Models\AttackStrategy;
use Illuminate\Support\Arr; // Use Laravel's Array helper
use Illuminate\Support\Facades\Log;
use Throwable; // Import Throwable for exception catching

class TacticalService
{
    public function getTacticalPlan(PrimordialDuck $duck): array
    {
        // 1. Get weaknesses (using the correct relationship name from your model)
        // Selecting specific columns is slightly cleaner
        $weaknesses = $duck->DuckWeaknesses()->get(['weaknesses.name', 'weaknesses.description']);

        // 2. Get all strategies
        $allStrategies = AttackStrategy::all();
        Log::info("TacticalPlan: Found {$allStrategies->count()} total strategies.");

        // 3. Filter recommended strategies
        $recommendedStrategies = $allStrategies->filter(function (AttackStrategy $strategy) use ($duck) {
            Log::debug("TacticalPlan: Evaluating Strategy ID: {$strategy->id} ('{$strategy->name}')");

            // Logic should be an array due to $casts in the AttackStrategy model
            $logic = $strategy->trigger_logic;

            // Check if logic is a valid array
            if (!is_array($logic)) {
                Log::warning("TacticalPlan: Trigger logic is not an array for Strategy ID {$strategy->id}. Check \$casts in AttackStrategy.php.");
                return false;
            }

            // If logic is empty, consider it valid (adjust if needed)
            if (empty($logic)) {
                Log::debug("TacticalPlan: Strategy ID {$strategy->id} has no trigger_logic. Including.");
                return true;
            }

            // Ensure required keys exist in the logic array
            if (!Arr::has($logic, ['field', 'operator', 'value'])) {
                Log::warning("TacticalPlan: Invalid trigger_logic structure for Strategy ID {$strategy->id}: " . json_encode($logic));
                return false;
            }

            $field = $logic['field'];
            $operator = $logic['operator'];
            $value = $logic['value'];

            // Safely get the attribute value from the duck model
            // Use $duck->getAttribute() which doesn't trigger 'magic method' warnings
            $duckValue = $duck->getAttribute($field);

            // Check if the attribute actually exists and has a value
            if ($duckValue === null && !$duck->hasAttribute($field)) {
                Log::warning("TacticalPlan: Field '{$field}' not found or is null on Duck (ID: {$duck->id}) for Strategy ID {$strategy->id}.");
                return false;
            }

            Log::debug("TacticalPlan: Comparing Strategy ID {$strategy->id}: Duck Field '{$field}' ({$duckValue}) {$operator} {$value}");

            // Perform the comparison
            try {
                switch ($operator) {
                    case '>': return $duckValue > $value;
                    case '<': return $duckValue < $value;
                    case '>=': return $duckValue >= $value;
                    case '<=': return $duckValue <= $value;
                    case '=':
                    case '==': return $duckValue == $value; // Use == for loose comparison if types might differ
                    case '!=': return $duckValue != $value;
                    case 'in':
                        if (!is_array($value)) {
                            Log::warning("TacticalPlan: Value for 'in' operator must be an array in Strategy ID {$strategy->id}.");
                            return false;
                        }
                        return in_array($duckValue, $value);
                    // Add 'contains' example for checking classifications (if needed)
                    case 'contains':
                        if (!is_array($duckValue)) {
                            Log::warning("TacticalPlan: Field '{$field}' must be an array for 'contains' operator in Strategy ID {$strategy->id}.");
                            return false;
                        }
                        return in_array($value, $duckValue); // Checks if $value exists within the $duckValue array
                    default:
                        Log::warning("TacticalPlan: Unsupported operator '{$operator}' in Strategy ID {$strategy->id}.");
                        return false;
                }
            } catch (Throwable $e) { // Catch any unexpected error during comparison
                Log::error("TacticalPlan: Error during comparison for Strategy ID {$strategy->id}: " . $e->getMessage());
                return false;
            }

        })->map(function (AttackStrategy $strategy) {
            // Format the output (cleaner without braces for simple returns)
            return ['id' => $strategy->id, 'name' => $strategy->name, 'description' => $strategy->description];
        })->values(); // Reset keys for a clean JSON array []

        Log::info("TacticalPlan: {$recommendedStrategies->count()} recommended strategies found.");

        // 4. Return the complete plan
        return [
            // Use snake_case for consistency in JSON keys
            'known_weaknesses' => $weaknesses,
            'recommended_strategies' => $recommendedStrategies,
        ];
    }
}
