# Partner demo

Partner demo project

### Development

Trigger the console to see valid commands
```shell script
./console
```

Start dev environment
```shell script
./console upd # run environment detached
# or
./console up # run environment attached (tail -f php and mysql logs)
```

Connect to container (`fpm-php` default)
```shell script
./console bash
```

Now that you have the environment up and running you can stop by:
 - Pressing keys `ctrl+c` when attached (`./console up`), then
 - Running `./console down`

