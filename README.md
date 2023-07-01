
# Walking Duck

This is a small Laravel project to demonstrate basic functionality.

It's a duck, yes, a duck.

A living, breathing and walking duck.

## Features

- Our duck can either be dead or alive.
- When it's alive, it will breathe, and you can ask it to walk in a specific direction.
- You can command the duck to stop walking, and stop breathing.
- You can also command the duck to turn in a different direction.
- Finally, you can kill the duck when you're done with it.


## Installation
### Requirements
To install and use Walking Duck, a basic knowledge of setting up Laravel is required.

### Setup
Clone the repository into your local environment and set up your .env file, then specify the data storage location as follows:

```bash
  FLATFILE_PATH=app/data/data.flat
```
Then run:
   ```bash
  composer install
``` 

Your duck should be ready to use now.
## Usage/Examples

### Status
To view the duck's current status, run:

```
php artisan duck:manage status
```

### Hatch your duck
To start managing your duck, you must first hatch it to make sure it's alive
```
php artisan duck:manage hatch
```
### Kill your duck
When you're ready to kill your duck, you can do it running this command:
```
php artisan duck:manage kill
```

### Make your duck walk
Your duck will walk on command in the direction you order it too,
first, set the direction (N, S, E, W), and then command it to walk.
```
php artisan duck:manage turn [direction]
php artisan duck:manage walk
```
Once your duck is walking, you can check its status to see where it's at, and you can order it to turn again.

You can also ask your duck to stop walking
```
php artisan duck:manage stop
```


### Your duck's breathing
As any other living creature, your duck needs to breathe to continue living.
Your duck will start breathing by itself once it's hatched, and will continue breathing normally until it dies, or it's commanded to stop breathing.

***WARNING: Your duck will eventually die if it's not breathing, it won't last longer than a minute!***
```
php artisan duck:manage stop-breathing
php artisan duck:manage breathing
```

## Public API

Walking Duck exposes a public endpoint to get the duck's current status.

#### Get duck's status 

```http
  GET /api/status
```


## Authors

- [@ojperez](https://www.github.com/ojperez)

