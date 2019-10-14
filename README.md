# Partner demo

Partner demo project

### Development

Trigger the console to see valid commands
```shell script
./console
```

Start the dev environment (detached)
```shell script
./console upd # run environment detached
# or
./console up # run environment attached (tail -f php and mysql logs)
```

Connect to `fpm-php` container
```shell script
./console bash
```

Now that you have the environment up and running you can stop by:
 - pressing keys `ctrl+c` when attached (`./console up`)
 - `./console down` when detached (`./console upd`)

