# Desk.com to Zendesk Command-line Utility

This is a command line utility is for transferring content from Desk.com to Zendesk written in PHP

## Usage

1. Copy `.env.dist` to `.env` and update the file with your correct username and password
2. Run `composer install`
3. To list available commands run `./bin/console`
4. To list articles run `./bin/console desk-com:articles:list`



## Docker

```bash
$ docker-compose build
$ docker-compose run console
$ docker-compose run console desk-com:articles:list
```
