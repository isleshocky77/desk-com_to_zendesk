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

## License ##

    desk-com_to_zendesk is licensed under GPLv3.

    desk-com_to_zendesk is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    desk-com_to_zendesk is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with ninja-forms-mautic.  If not, see <http://www.gnu.org/licenses/>.
