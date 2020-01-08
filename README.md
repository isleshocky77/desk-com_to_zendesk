# Desk.com to Zendesk Command-line Utility

This is a command line utility is for transferring content from [Desk.com] to [Zendesk] written in PHP

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
$ docker-compose run console desk-com:topics:sync --dry-run 363224296234
$ docker-compose run console desk-com:articles:sync --dry-run 363224296234 --user-segment-id=3600443271554 --permission-group-id=33563674
```

## Sample Output

```
$ docker-compose run console desk-com:topics:sync 3603453496234
+---------+-------------------------------------+--------------+-------------------------------------+---------+
| Desk ID | Desk Name                           | Zendesk ID   | Zendesk Name                        | Status  |
+---------+-------------------------------------+--------------+-------------------------------------+---------+
| 642539  | Topic Name                          | 360042333613 | Topic Name                          | Matched |
| 642540  | Topic Name 2                        | 360042333614 | Topic Name 2                        | Saved   |
+---------+----------------------------------- Total Topics : 2 ------------------------------------+---------+
```

## Resources

### Desk.com

* [Desk.com][Desk.com]
* [Desk.com Developer][Desk.com Developer]
* [Desk.com API for Articles][Desk.com API for Articles]

[Desk.com]: https://desk.com
[Desk.com Developer]: https://dev.desk.com/
[Desk.com API for Articles]: https://dev.desk.com/API/articles/

### Zendesk

* [Zendesk][Zendesk]
* [Zendesk Developer][Zendesk Developer]
* [Zendesk API for Articles][Zendesk API for Articles]

[Zendesk]: https://www.mautic.org/
[Zendesk Developer]: https://developer.zendesk.com
[Zendesk API for Articles]: https://developer.zendesk.com/rest_api/docs/help_center/articles



