Angel CMS Workbench
===================

This is a default Laravel 4.1 installation that loads the Angel CMS packages as [GIT submodules](http://git-scm.com/book/en/Git-Tools-Submodules) in the [Laravel Workbench](http://laravel.com/docs/packages#creating-a-package).

This repository is only needed if you intend to develop the Angel core or any modules for it.

Installation
------------
Fork this repository.

Clone your fork locally.

First, temporarily comment out the `'Angel\Core\CoreServiceProvider'` in `app/config/app.php`.

From the root of your local clone:
```shell
composer install # Install Laravel
php artisan angel:workbench -a  # Get the latest version of all workbenches
                                # and publish their assets.
cd app/config
# Copy the example configurations so you can edit them for your environment...
cp database.php.example database.php # and edit to taste
cp mail.php.example mail.php # and edit to taste
cp workbench.php.example workbench.php # and edit to taste
cd - # Back to the project root
cd workbench/angel/core
composer install # Install the Angel core's dependencies
cd - # Back to the project root
php artisan dump-autoload
```

Now uncomment the `'Angel\Core\CoreServiceProvider'` in `app/config/app.php`.

Finally:
```shell
php artisan angel:reset # Reset the database and run all migrations for all benches.
                        # You'll be using this fairly often, as well.
```

Install a Module
----------------
To install any modules you'd like to work on as benches:
```shell
# Example:  modals
git submodule add git@github.com:JVMartin/angel-modals.git workbench/angel/modals
php artisan angel:workbench -a
php artisan angel:reset
php artisan dump-autoload
```
...and add `'Angel\Modals\ModalsServiceProvider'` to `app/config/app.php`.

Create a Module
----------------
To create a module:
```shell
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
cd -                                           # Jump back to the project root.
rm -rf workbench/angel/blog                    # Delete the workbench we just created.
git submodule add git@github.com:MyName/angel-blog.git workbench/angel/blog
php artisan angel:workbench
php artisan angel:reset
php artisan dump-autoload
```
...and add `'Angel\Blog\BlogServiceProvider'` to `app/config/app.php`.

Sync Upstream
-------------

To [sync your fork](https://help.github.com/articles/syncing-a-fork) with updates from the original repo:
```shell
git remote add upstream git@github.com:angel-cms/angel-workbench.git # Make the original repo a remote
git fetch upstream
git merge upstream/master
```
