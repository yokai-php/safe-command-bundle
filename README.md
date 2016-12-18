YokaiSafeCommandBundle
======================

[![Build Status](https://api.travis-ci.org/yokai-php/safe-command-bundle.png?branch=master)](https://travis-ci.org/yokai-php/safe-command-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yokai-php/safe-command-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yokai-php/safe-command-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yokai-php/safe-command-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yokai-php/safe-command-bundle/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9fa4fc24-ccc1-427e-aa97-9350b23a61b8/mini.png)](https://insight.sensiolabs.com/projects/9fa4fc24-ccc1-427e-aa97-9350b23a61b8)


Did you find yourself ashamed, running command in the wrong environment ?

"Oups... I dropped the database in the prod environment..." - A guy that lost his job

This bundle want to help. Using configuration, define commands that you are not expecting to be used in your environment.

That's it...


Installation
------------

### Add the bundle as dependency with Composer

``` bash
$ php composer.phar require yokai/safe-command-bundle
```

### Enable the bundle in the kernel

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Yokai\SafeCommandBundle\YokaiSafeCommandBundle(),
    ];
}
```

### Configuration

```
yokai_safe_command:
    enabled: true
    commands:
        enabled: true
        config:
            enabled: true
            commands:
                - 'config:dump-reference'
        doctrine:
            enabled: true
            commands:
                - 'doctrine:database:drop'
                - 'doctrine:mapping:convert'
                - 'doctrine:mapping:import'
                - 'doctrine:schema:drop'
                - 'doctrine:schema:validate'
        debug:
            enabled: true
            commands:
                - 'debug:config'
                - 'debug:container'
                - 'debug:event-dispatcher'
                - 'debug:router'
                - 'debug:swiftmailer'
                - 'debug:translation'
                - 'debug:twig'
        lint:
            enabled: true
            commands:
                - 'lint:twig'
                - 'lint:yaml'
        server:
            enabled: true
            commands:
                - 'server:run'
                - 'server:start'
                - 'server:status'
                - 'server:stop'
        translation:
            enabled: true
            commands:
                - 'translation:update'
        misc:
            enabled: true
            commands: {  }
    environments:
        enabled: false
        environments:
            - prod
```


MIT License
-----------

License can be found [here](https://github.com/yokai-php/safe-command-bundle/blob/master/LICENSE).


Authors
-------

The bundle was originally created by [Yann Eugoné](https://github.com/yann-eugone).

See the list of [contributors](https://github.com/yokai-php/safe-command-bundle/contributors).
