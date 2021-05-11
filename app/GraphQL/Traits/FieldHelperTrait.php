<?php

namespace App\GraphQL\Traits;

use App\GraphQL\Types\Common\CountryType;
use App\GraphQL\Types\UserType;
use GraphQL\Type\Definition\Type;
use GraphQL;

trait FieldHelperTrait
{
    protected $defaultNameMax = 40;

    protected function createField($name, $type, $rules = null, $defaultValue = null): array
    {
        $field = [
            'type' => $type,
        ];
        if ($rules !== null) {
            $field['rules'] = $rules;
        }
        if ($defaultValue !== null) {
            $field['defaultValue'] = $defaultValue;
        }

        return [$name => $field];
    }

    protected function idField($required = true, $integer = true, $rules = null): array
    {
        $r = [];
        if ($integer) {
            $r[] = 'integer';
            $type = Type::int();
        } else {
            $type = Type::string();
        }
        if ($required) {
            $r[] = 'required';
            $type = Type::nonNull($type);
        }
        if ($rules) {
            $r = array_merge($r, $rules);
        }

        return $this->createField('id', $type, $r);
    }

    protected function userIdField(): array
    {
        return [
            'user_id' => [
                'type' => Type::int(),
            ],
        ];
    }

    protected function userField(): array
    {
        return $this->createField('user', GraphQL::type(UserType::$name));
    }

    protected function relatedUserField(): array
    {
        return $this->createField('related_user', GraphQL::type(UserType::$name));
    }

    protected function relatedUserIdField(): array
    {
        return [
            'related_user_id' => [
                'type' => Type::int(),
            ],
        ];
    }

    protected function statusField($default = null): array
    {
        return $this->createField('status', Type::boolean(), null, $default);
    }

    protected function nameField($required = false, $max = null, $rules = null): array
    {
        $r = []; $type = Type::string();
        if ($required) {
            $r[] = 'required';
            $type = Type::nonNull($type);
        } else {
            $r[] = 'nullable';
        }
        if ($max === null) {
            $max = $this->defaultNameMax;
        }
        if ($max) {
            $r[] = 'max:'.$max;
        }
        if ($rules) {
            $r = array_merge($r, $rules);
        }

        return $this->createField('name', $type, $r);
    }

    protected function descriptionField(): array
    {
        return $this->createField('description', Type::string());
    }

    protected function countryField(): array
    {
        return $this->createField('country', GraphQL::type(CountryType::$name));
    }

    protected function timestampField(): array
    {
        return [
            'created_at' => [
                'type' => Type::string(),
            ],
            'updated_at' => [
                'type' => Type::string(),
            ],
        ];
    }
}
