<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\EnvironmentVariable;
use App\Exception\ScriptLanguageNotSupported;
use App\Traits\SerializeToIso8601;

/**
 * Represents an Eloquent model of a Script
 *
 * @package app\Model
 *
 * @property integer id
 * @property string key
 * @property string title
 * @property text description
 * @property string language
 * @property text code
 *
 * @OA\Schema(
 *   schema="scriptsEditable",
 *   @OA\Property(property="id", type="string", format="id"),
 *   @OA\Property(property="title", type="string"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="language", type="string"),
 *   @OA\Property(property="code", type="string"),
 * ),
 * @OA\Schema(
 *   schema="scripts",
 *   allOf={@OA\Schema(ref="#/components/schemas/scriptsEditable")},
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *
 */
final class Script extends Model
{
    use SerializeToIso8601;
    use ScriptDockerTrait;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    private static array $scriptFormats = [
        'application/x-php' => 'php',
        'application/x-lua' => 'lua',
    ];

    /**
     * Validation rules
     *
     * @param $existing
     *
     * @return array{key: string, title: mixed[]|string, language: string}
     */
    public static function rules($existing = null): array
    {
        $rules = [
            'key' => 'unique:scripts,key',
            'title' => 'required|unique:scripts,title',
            'language' => 'required|in:php,lua'
        ];
        if ($existing) {
            // ignore the unique rule for this id
            $rules['title'] = [
                'required',
                'string',
                Rule::unique('scripts')->ignore($existing->id, 'id')
            ];
        }
        return $rules;
    }

    /**
     * Executes a script given a configuration and data input.
     */
    public function runScript(array $data, array $config): array
    {
        $code = $this->code;
        $language = $this->language;

        $variablesParameter = [];
        EnvironmentVariable::chunk(50, function ($variables) use (&$variablesParameter): void {
            foreach ($variables as $variable) {
                $variablesParameter[] = $variable['name'] . '=' . $variable['value'];
            }
        });

        if ($variablesParameter) {
            $variablesParameter = "-e " . implode(" -e ", $variablesParameter);
        } else {
            $variablesParameter = [];
        }

        $config = match (strtolower((string) $language)) {
            'php' => [
                'image' => 'processmaker/executor:php',
                'command' => 'php /opt/executor/bootstrap.php',
                'parameters' => $variablesParameter,
                'inputs' => [
                    '/opt/executor/data.json' => json_encode($data, JSON_THROW_ON_ERROR),
                    '/opt/executor/config.json' => json_encode($config, JSON_THROW_ON_ERROR),
                    '/opt/executor/script.php' => $code
                ],
                'outputs' => [
                    'response' => '/opt/executor/output.json'
                ]
            ],
            'lua' => [
                'image' => 'processmaker/executor:php',
                'command' => 'lua5.3 /opt/executor/bootstrap.lua',
                'parameters' => $variablesParameter,
                'inputs' => [
                    '/opt/executor/data.json' => json_encode($data, JSON_THROW_ON_ERROR),
                    '/opt/executor/config.json' => json_encode($config, JSON_THROW_ON_ERROR),
                    '/opt/executor/script.php' => $code
                ],
                'outputs' => [
                    'response' => '/opt/executor/output.json'
                ]
            ],
            default => throw new ScriptLanguageNotSupported($language),
        };

        $response = $this->execute($config);
        $returnCode = $response['returnCode'];
        $errorContent = $response['output'];
        $output = $response['outputs']['response'];

        if ($returnCode) {
            // Has an error code
            return [
                'output' => implode($errorContent, "\n")
            ];
        }
        // Success
        $response = json_decode((string) $output, true, 512, JSON_THROW_ON_ERROR);
        return [
            'output' => $response
        ];
    }

    /**
     * Get the language from a script format string.
     *
     * @param string $format
     *
     * @return string
     */
    public static function scriptFormat2Language($format)
    {
        return static::$scriptFormats[$format];
    }
}
