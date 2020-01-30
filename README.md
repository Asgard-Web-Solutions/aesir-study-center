# Purpose
This is a quick website that I threw together to allow me to study for various upcoming certification exams. It uses interval training, where the time between seeing the same question again changes depending on how you answer it. If you answer correctly, you see it less often. If you answer incorrectly, you see it more often.

# Installation
* git clone the repository
* copy .env.example to .env
* add your database information to .env
* composer update
* php artisan voyager:install
* Register an account
* php artisan voyager:admin <your registered email address>
* go to your-domain/admin

The voyager relationships will have to be recretead. Use their documentation and the BREAD options to link sets to questions, and then questions to answers in both directions. Then you can add questions and answers through this menu. I know it isn't elegant but it was the quickest way to get up and running and I only had 14 days to study for the exam and create this application.

I do plan on coming back later and building a proper way to add questions to the database.