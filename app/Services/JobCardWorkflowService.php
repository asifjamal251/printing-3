<?php

namespace App\Services;

class JobCardWorkflowService
{
    public static function determineWorkflow(array $attributes)
    {
        $workflows = config('jobcard_workflows.workflows');
        $default   = config('jobcard_workflows.default');

        // normalize incoming attributes ONCE
        $normalizedAttributes = self::normalizeArray($attributes);

        foreach ($workflows as $workflow) {
            $match = true;

            foreach ($workflow['rules'] as $field => $ruleValue) {

                if (!array_key_exists($field, $normalizedAttributes)) {
                    $match = false;
                    break;
                }

                $attrValue = $normalizedAttributes[$field];
                $ruleValue = self::normalizeValue($ruleValue);

                // ---------- RULE VALUE IS ARRAY (OR) ----------
                if (is_array($ruleValue)) {
                    if (!in_array($attrValue, $ruleValue, true)) {
                        $match = false;
                        break;
                    }
                }
                // ---------- RULE VALUE IS STRING ----------
                else {
                    if ($attrValue !== $ruleValue) {
                        $match = false;
                        break;
                    }
                }
            }

            if ($match) {
                return $workflow['stages'];
            }
        }

        return $default;
    }

    /* ---------------- HELPERS ---------------- */

    private static function normalizeArray(array $data): array
    {
        $out = [];

        foreach ($data as $key => $value) {
            $out[$key] = self::normalizeValue($value);
        }

        return $out;
    }

    private static function normalizeValue($value)
    {
        // NULL / empty → none
        if ($value === null || $value === '') {
            return 'none';
        }

        // ARRAY → normalize each value
        if (is_array($value)) {
            return array_map(fn ($v) => self::normalizeValue($v), $value);
        }

        // STRING
        return strtolower(trim((string) $value));
    }
}