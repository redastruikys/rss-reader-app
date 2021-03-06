# RSS Reader

Requirements:
  - PHP 7.4 (my was 7.4.5)
  - MySQL
  - Node.js / Npm
  - Git
  - Composer
  - Sass
  - Symfony 5
  - Yarn (optional)

### Installation

Clone project:

```sh
$ git clone https://github.com/redastruikys/rss-reader-app.git
$ cd rss-reader-app
```

Install dependencies:

```sh
$ composer install
$ npm install
```

Database setup:
  - Manually create new database example: `rss_reader_app`
  - Inside `.env` file change `DATABASE_URL` value according to your database credentials. Something like:
    `mysql://db_user_name:db_user_pass@127.0.0.1:3306/database_name?serverVersion=server_version`
 - Make database migrations
    ```sh
    $ php bin/console doctrine:migrations:migrate
    ```

Generate public assets:
```sh
$ npm run dev
```

Starting local web server:
```sh
$ php bin/console server:run
```

Now you can go to application website using address http://127.0.0.1:8000

### App console commands
  - `app:fetch_common_words`
    Will generate most common words list from source (Default: https://en.wikipedia.org/wiki/Most_common_words_in_English) and save it to config.
    This list is pre-generated by default, you can find it in `\config\packages\common_words_list.yaml`
  - To re-generate words list with default parameters run:
    ```sh
    $ php bin/console app:fetch_common_words
    ```
  - To get information of available command parameters run:
    ```sh
    $ php bin/console help app:fetch_common_words
    ```
