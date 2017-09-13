<?php

declare(strict_types=1);

namespace Scalp\Tests\Type {
    const functionWithReturnType = __NAMESPACE__.'\functionWithReturnType';

    function functionWithReturnType(): int
    {
        return 42;
    }

    const functionWithoutReturnType = __NAMESPACE__.'\functionWithoutReturnType';

    function functionWithoutReturnType()
    {
        return 42;
    }
}
