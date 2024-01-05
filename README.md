# YokaiSafeCommandBundle

[![Tests](https://img.shields.io/github/actions/workflow/status/yokai-php/safe-command-bundle/tests.yml?branch=master&style=flat-square&label=tests)](https://github.com/yokai-php/safe-command-bundle/actions)
[![Coverage](https://img.shields.io/codecov/c/github/yokai-php/safe-command-bundle?style=flat-square)](https://codecov.io/gh/yokai-php/safe-command-bundle)
[![Contributors](https://img.shields.io/github/contributors/yokai-php/safe-command-bundle?style=flat-square)](https://github.com/yokai-php/safe-command-bundle/graphs/contributors)

[![License](https://img.shields.io/packagist/l/yokai/safe-command-bundle?style=flat-square)](https://packagist.org/packages/yokai/safe-command-bundle/stats)
[![Latest Stable Version](https://img.shields.io/packagist/v/yokai/safe-command-bundle?style=flat-square)](https://packagist.org/packages/yokai/safe-command-bundle)
[![Current Unstable Version](https://img.shields.io/packagist/v/yokai/safe-command-bundle?include_prereleases&style=flat-square)](https://packagist.org/packages/yokai/safe-command-bundle)
[![Downloads Monthly](https://img.shields.io/packagist/dm/yokai/safe-command-bundle?style=flat-square)](https://packagist.org/packages/yokai/safe-command-bundle/stats)
[![Total Downloads](https://img.shields.io/packagist/dt/yokai/safe-command-bundle?style=flat-square)](https://packagist.org/packages/yokai/safe-command-bundle/stats)


Did you find yourself ashamed, running command in the wrong environment ?

"Oups... I dropped the database in the prod environment..." - A guy that lost his job

This bundle want to help. Using configuration, define commands that you are not expecting to be used in your environment.

That's it...


## Installation

### Add the bundle as dependency with Composer

``` bash
composer require yokai/safe-command-bundle
```

### Enable the bundle in the kernel

``` php
<?php
// config/bundles.php

return [
    // ...
    Yokai\SafeCommandBundle\YokaiSafeCommandBundle::class => ['prod' => true],
];
```

> [!NOTE]
> The bundle is enabled only for `prod` here, but you are free to do whatever you want.

### Configuration

The bundle comes with some commands disabled by default (from Symfony's standards).

That "standard" command list can be overridden:
```
# config/packages/yokai_safe_command.yaml
when@prod:
    yokai_safe_command:
        standard: []
```

> [!NOTE]
> "standard" disabled commands are viewable via the command:
> ```
> bin/console config:dump-reference yokai_safe_command
> ```

And you can also add your own commands to the list:
```
# config/packages/yokai_safe_command.yaml
when@prod:
    yokai_safe_command:
        custom:
          - 'vendor:my:dev-command'
          - 'app:my:dev-command'
```

> [!NOTE]
> `standard` and `custom` configs are merged together to create the final list of disabled commands.


## License

This library is under MIT [LICENSE](LICENSE).


## Authors

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).

See the list of [contributors](https://github.com/yokai-php/safe-command-bundle/contributors).
