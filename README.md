To serve (development) the application:

-   PHP 8.2 - 8.3 is required
-   Node.js 22 is required
-   For now, installing the Frontend CMS repository in the same directory is required - [Repo Here](https://github.com/cima-alfa/next-laravel-cms-front)
-   Do the following:
    -   Run `composer install`
    -   Run `pnpm install`
    -   Duplicate the `.env.example` file and rename it to `.env`
    -   Run `php artisan migrate --seed` and let artisan create the `database.sqlite` file (if you left the `sqlite` db connection set in your `.env` file)
    -   Run `php artisan key:generate`
    -   Run `composer run dev`
-   Default hosts for frontend and api are `localhost:3000` and `localhost:8000` respectively

To install the Frontend CMS repository, see the reame: https://github.com/cima-alfa/next-laravel-cms-front/blob/main/README.md
