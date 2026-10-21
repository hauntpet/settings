<?php

namespace Haunt\Settings\Models;

use Haunt\Framework\Extend\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use LumiteStudios\Common\Model\Concerns\HasCacheKey;

/**
 ***** Properties
 *
 * @property int $id
 * @property string $key
 * @property array<string, string[]> $rules
 * @property object $created_at
 * @property object $updated_at
 ***** Attributes
 * @property string $name
 * @property mixed $value
 ***** Relationships
 ***** Scopes
 ***** Methods
 *
 * @method void set(mixed $value, bool $validate = true)
 * @method static static add(string $key, mixed $value, array $rules = [])
 * @method static void validateValue(mixed $value, array $rules)
 */
class Setting extends Model
{
    use HasCacheKey;

    protected $table = 'settings';

    protected $casts = [
        'rules' => 'array',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (): string => Str::headline($this->key),
        );
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn (): mixed => cache()->get($this->getCacheKey()),
        );
    }

    public static function forKey(string $key): mixed
    {
        return static::where('key', $key)->first()->value;
    }

    public function set(mixed $value, bool $validate = true): void
    {
        if ($validate) {
            $this->validateValue($value, $this->rules);
        }
        cache()->forever($this->getCacheKey(), $value);
    }

    public static function add(string $key, mixed $value, array $rules = []): static
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['rules' => $rules]
        );

        $setting->set($value);

        return $setting;
    }

    public static function validateValue(mixed $value, array $rules): void
    {
        $validator = Validator::make(['value' => $value], ['value' => $rules]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }
}
