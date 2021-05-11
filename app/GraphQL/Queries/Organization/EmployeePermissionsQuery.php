<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Organization;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Ad\AdConfig;
use App\Models\Ad\AdLimitConfig;
use App\Models\Ad\AdPlace;
use App\Models\Ad\AdPlaceConfig;
use App\Models\Ad\AdSource;
use App\Models\Advertiser\Advertiser;
use App\Models\Common\AndroidVersion;
use App\Models\Component\Component;
use App\Models\Component\ComponentConfig;
use App\Models\Component\ComponentGroup;
use App\Models\CreditCard\CreditCard;
use App\Models\DeviceGroup\DeviceGroup;
use App\Models\Download\DownloadConfig;
use App\Models\Facebook\FacebookAccount;
use App\Models\Facebook\FacebookBusiness;
use App\Models\Facebook\FacebookPubProperty;
use App\Models\Game\Game;
use App\Models\Game\GameCategory;
use App\Models\Offer\Offer;
use App\Models\Operation\OperationConfig;
use App\Models\Operation\OperationPolicy;
use App\Models\Permission;
use App\Models\Plugin\Plugin;
use App\Models\Plugin\PluginVersion;
use App\Models\Proxy\ProxyServer;
use App\Models\Publisher\Publisher;
use App\Models\Publisher\PublisherAggregateDaily;
use App\Models\Publisher\PublisherSdkPackage;
use App\Models\Role;
use App\Models\Sdk\SdkVersion;
use App\Models\Sync\SyncConfig;
use App\Models\Tidb\Facebook\FacebookAdNetworkAnalyticsDaily;
use App\Models\User;
use App\Models\User\Employee;
use App\Models\Google\GooglePlayApp;
use App\Models\Tidb\Device;
use Closure;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\SelectFields;
use Str;

class EmployeePermissionsQuery extends BaseQuery
{
    public static $name = 'EmployeePermissions';

    public function type(): Type
    {
        return GraphQL::type('Any');
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return static::canAny(Role::class, 'read');
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return self::resolvePermissions();
    }

    public static function resolvePermissions(): array
    {
        /** @var User $authUser */
        $authUser = auth_user();
        $ret = [];
        $pms = [
            [
                'name' => '组织',
                'values' => [
                    [
                        'key' => Role::PermissionName,
                        'name' => '角色',
                        'permissions' => [
                            '' => ['create'],
                            'all' => ['list', 'read', 'update', 'delete'],
                        ],
                    ],
                    [
                        'key' => Employee::PermissionName,
                        'name' => '用户',
                        'permissions' => [
                            '' => ['create'],
                            'own' => ['list', 'read', 'enable', 'disable', 'update', 'delete', 'login'],
                            'all' => ['list', 'read', 'enable', 'disable', 'update', 'delete', 'login'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($pms as $pm) {
            $module = [
                'name' => $pm['name'],
                'values' => [],
            ];
            foreach ($pm['values'] as $section) {
                $moduleValue = [
                    'name' => $section['name'],
                    'values' => [],
                ];
                foreach ($section['permissions'] as $group => $permissions) {
                    $pmGroup = [
                        'name' => Str::ucfirst($group),
                        'values' => [],
                    ];

                    foreach ($permissions as $name => $displayName) {
                        if (is_int($name)) {
                            $name = $displayName;
                            $displayName = trim(Str::ucfirst($name));
                        }

                        $name = $section['key'].'-'.$name;
                        if ($group) {
                            $name .= '-'.$group;
                        }
                        if (!$authUser->isRoot() &&
                            !($authUser->employee && $authUser->employee->checkPermissionTo($name))
                        ) {
                            continue;
                        }
                        Permission::findOrCreate($name, Employee::$guard_name);
                        $pmGroup['values'][] = [
                            'name' => $name,
                            'display_name' => $displayName,
                        ];
                    }

                    if (!empty($pmGroup['values'])) {
                        $moduleValue['values'][] = $pmGroup;
                    }
                }

                if (!empty($moduleValue['values'])) {
                    $module['values'][] = $moduleValue;
                }
            }

            if (!empty($module['values'])) {
                $ret[] = $module;
            }
        }

        return $ret;
    }
}
