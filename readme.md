Angel CMS Workbench
===================

This is a default Laravel 4.1 installation that loads the Angel CMS packages as [GIT submodules](http://git-scm.com/book/en/Git-Tools-Submodules) in the [Laravel Workbench](http://laravel.com/docs/packages#creating-a-package).

This repository is only needed if you intend to develop the Angel core or any modules for it.

Installation
------------
Fork this repository.

Clone your fork locally.

From the root of your local clone:
```
composer install # Install all dependencies
php artisan angel:workbench   # Get the latest version of all workbenches.  Right now,
                              # it's just the core, but in the future you can use this
                              # same command to update all of your benches.
cd app/config
cp database.php.example database.php
cp mail.php.example mail.php
cp workbench.php.example workbench.php
cd -
cd workbench/angel/core
composer install # Install the core's dependencies
```

Now, edit those config files you copied the examples from to set up your settings for your database, mail, and workbench author credentials.

Finally:
```
php artisan angel:reset # Reset the database and run all migrations for all benches.
                        # You'll be using this fairly often, as well.
```

Then, install any modules you'd like to work on as benches.
```
# Example:  modals
git submodule add git@github.com:JVMartin/angel-modals.git workbench/angel/modals
php artisan angel:workbench
```

Or create a module:
```
# Let's create a `blog` module.
# Create a blank repository on GitHub called `angel-blog`
php artisan workbench angel/blog --resources   # Create a workbench.
cd workbench/angel/blog                        # Enter the workbench we just created.
git clone git@github.com:MyName/angel-blog.git # Clone the empty repository into the bench folder.
cp -r angel-blog/.git .                        # Copy out the .git folder into the bench folder.
rm -rf angel-blog                              # Delete the now unnecessary angel-blog folder.
git add -A                                     # Stage all files.
git commit -m "Initial commit."                # Make the initial commit.
git push                                       # Push it up.
```
