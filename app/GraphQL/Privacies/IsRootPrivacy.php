<?php

declare(strict_types=1);

namespace App\GraphQL\Privacies;

use Rebing\GraphQL\Support\Privacy;

class IsRootPrivacy extends Privacy
{
    public function validate(array $args): bool
    {
        return auth_user(false) && auth_user(false)->isRoot();
    }
}
