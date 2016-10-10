# Cache

ONGR Settings Bundle uses Doctrines `PhpFileCache`. It works very well, however,
it operates correctly only on a single server. If your system uses 
numerous servers to improve its performance, the `FileSystemCache` will
not be able to clear the cache correctly throughout your architecture.

There are, in essence, two ways that you can fix this. Below is information
about both of them.

## Overriding the service

First one is simply overriding the cache service. You will need to use a different cache 
system from `Doctrine`. More information on that can be found [here][1].
Once you've picked a right class for the job, say `Memcache`, you will need
to override the `ong_settings.cache_provider` in your projects `services.yml`
file. Here is an example of the configuration:

```yaml

ong_settings.cache_provider:
        class: Doctrine\Common\Cache\PhpFileCache
        arguments: ["%kernel.cache_dir%/ongr/settings", ".ongr.settings_cache.php"]

```

## Writing an event listener

The second option is to write an event listener and 

[1]: https://symfony.com/doc/current/bundles/DoctrineCacheBundle/index.html