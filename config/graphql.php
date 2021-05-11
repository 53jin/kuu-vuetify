<?php

declare(strict_types=1);

// use example\Mutation\ExampleMutation;
// use example\Query\ExampleQuery;
// use example\Type\ExampleRelationType;
// use example\Type\ExampleType;

use App\Http\Controllers\GraphQLController;

return [

    // The prefix for routes
    'prefix' => 'gql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{graphql_schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => GraphQLController::class.'@query',

    // Any middleware for the graphql route group
    'middleware' => [],

    // Additional route group attributes
    //
    // Example:
    //
    // 'route_group_attributes' => ['guard' => 'api']
    //
    'route_group_attributes' => [],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'query' => [
                // 'example_query' => ExampleQuery::class,
                App\GraphQL\Queries\MeQuery::class,
                App\GraphQL\Queries\AppQuery::class,

                App\GraphQL\Queries\Organization\RoleQuery::class,
                App\GraphQL\Queries\Organization\RolesQuery::class,
                App\GraphQL\Queries\Organization\AllRolesQuery::class,
                App\GraphQL\Queries\Organization\EmployeeQuery::class,
                App\GraphQL\Queries\Organization\EmployeesQuery::class,
                App\GraphQL\Queries\Organization\AllEmployeesQuery::class,
                App\GraphQL\Queries\Organization\EmployeePermissionsQuery::class,
            ],
            'mutation' => [
                // 'example_mutation'  => ExampleMutation::class,
                App\GraphQL\Mutations\User\UpdateUserMutation::class,
                App\GraphQL\Mutations\Organization\CreateRoleMutation::class,
                App\GraphQL\Mutations\Organization\UpdateRoleMutation::class,
                App\GraphQL\Mutations\Organization\CreateEmployeeMutation::class,
                App\GraphQL\Mutations\Organization\UpdateEmployeeMutation::class,
                App\GraphQL\Mutations\Organization\SendInviteEmailMutation::class,
            ],
            'middleware' => ['web'],
            'method' => ['post'],
        ],
        'user' => [
            'query' => [
                // App\GraphQL\Queries\MeQuery::class,
            ],
            'mutation' => [
                //
            ],
            'middleware' => [],
            'method' => ['post'],
        ],
    ],

    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        App\GraphQL\Scalars\AnyScalar::class,
        App\GraphQL\Inputs\QueryInput::class,
        App\GraphQL\Types\UserType::class,
        App\GraphQL\Types\AppType::class,


        App\GraphQL\Types\Organization\RoleType::class,
        App\GraphQL\Types\Organization\PermissionType::class,
        App\GraphQL\Types\Organization\EmployeeType::class,
        App\GraphQL\Inputs\Organization\CreateRoleInput::class,
        App\GraphQL\Inputs\Organization\UpdateRoleInput::class,
        App\GraphQL\Inputs\Organization\CreateEmployeeInput::class,
        App\GraphQL\Inputs\Organization\UpdateEmployeeInput::class,

        //input
        App\GraphQL\Inputs\User\UserInput::class,
    ],

    // The types will be loaded on demand. Default is to load all types on each request
    // Can increase performance on schemes with many types
    // Presupposes the config type key to match the type class name property
    'lazyload_types' => true,

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key' => 'variables',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://webonyx.github.io/graphql-php/security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => null,
        'disable_introspection' => false,
    ],

    /*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    /*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     */
    'graphiql' => [
        'prefix' => '/graphiql',
        'controller' => GraphQLController::class.'@graphiql',
        'middleware' => ['web'],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', false),
    ],

    /*
     * Overrides the default field resolver
     * See http://webonyx.github.io/graphql-php/data-fetching/#default-field-resolver
     *
     * Example:
     *
     * ```php
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     * },
     * ```
     * or
     * ```php
     * 'defaultFieldResolver' => [SomeKlass::class, 'someMethod'],
     * ```
     */
    'defaultFieldResolver' => null,

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,
];
