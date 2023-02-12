# Purpose
This is a quick website that I threw together to allow me to study for various upcoming certification exams. It uses interval training, where the time between seeing the same question again changes depending on how you answer it. If you answer correctly, you see it less often. If you answer incorrectly, you see it more often.

# Installation
* git clone the repository
* copy .env.example to .env
* add your database information to .env
* composer update
* run php artisan key:generate
* php artisan voyager:install
* Register an account
* php artisan voyager:admin <your registered email address>
* You should see the Manage Exams link at the top of the page now
