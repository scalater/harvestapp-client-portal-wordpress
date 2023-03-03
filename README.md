## HtmlCssToImage (wp-htmlcsstoimage)
WP plugin to connect with htmlcsstoimage.com service to generate images from html and css.

# Setup for development
If you want to install this plugin in your local for testing or develop. You need to read carefully the next sections.

### Requirements
- PHP 7.4
- WordPress latest
- WooCommerce latest

### Installation

* Composer
    * `composer install`
* Node
  * `npm install`

#### Troubleshooting
If you face composer memory problems like in the next line.

> `PHP Fatal error: Allowed memory size of XXXXXX bytes exhausted <...>`

Use the command

> `php -d memory_limit=-1 <composer path> <...>`

Source: [https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors](https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors)

### Testing
We use [codeception](https://codeception.com/) and webdriver.

Related commands for testing
* Run chromedriver before start executing the test
    * `vendor/bin/chromedriver --url-base=/wd/hub`
* Generate Class Test file
    * `vendor/bin/codecept g:cest acceptance <testName>`
* To run all the acceptance test from command line with steps
    * `vendor/bin/codecept run tests/acceptance/SiteNameCest.php --steps`
* To run specific file test from command line with steps
    * `vendor/bin/codecept run <path to the file> --steps`

## Contributing

The key words “MUST”, “MUST NOT”, “REQUIRED”, “SHALL”, “SHALL NOT”, “SHOULD”, “SHOULD NOT”, “RECOMMENDED”, “MAY”, and “OPTIONAL” in this document are to be interpreted as described in [RFC 2119](https://tools.ietf.org/html/rfc2119).

The branches `main` stand for last stable release and `develop` stand for next release.

The branches `main` and `develop` are protected. You SHOULD do your work in a feature branch named `feature/<name_of_issue>` and make a Pull Request.

Unless you are working on a task that integrates with something that’s only available in `main`, you should fork from `develop` and have your PRs against `develop`.

Merges into `main` and `develop` are REQUIRED to pass the PHPCS status check first. This is enforced in GitHub.

> When enabled, commits must first be pushed to another branch, then merged or pushed directly to a branch that matches this rule after status checks have passed.

You MUST your work in separate branches and create PRs to merge them into `develop` or `main`.

For merges into `develop` it is OPTIONAL to wait for anyone to review your PR manually. You just MUST make sure your code passes the PHPCS which is our bare minimum code quality check.

When you create a PR, you MAY merge the PR automatically when all tests pass.

After a PR is merged, GitHub will delete the head branch.

### Coding Standards

All code SHOULD adhere to the defined coding standards. Your code MAY deviate from the coding standards, but is then REQUIRED to document the reason.

External libraries SHOULD be added via a package manager (Composer/NPM). Code in any `vendor` dir is excluded from the PHPCS checks. You MUST quality check external libraries before adding them.

The coding standards are based on the WordPress coding standards. More specifically, the WordPress-Extra and WooCommerce-Core standards.
For sanity, we have added a few exceptions, so we’re allowing (and encouraging) using short array syntax, and you don’t have to (shouldn’t really) align all your `=`s and `=>`s (because that means you’ll later have to edit lines that should be unaffected by what you’re doing).

If you want to see the standards in detail, look at the file `phpcs.xml`.

To check your code against the coding standards run:
1. `composer install`
2. `./vendor/bin/phpcs --standard=phpcs.xml .`
3. `./vendor/bin/phpcbf --standard=phpcs.xml .`

### Language

All code, code comments, commit messages, descriptions etc. MUST be in English.

All customer facing output (i.e. all output in the frontend) MUST be in [Internationalization Functions](https://developer.wordpress.org/apis/handbook/internationalization/internationalization-functions/).

### Keep a changelog

All notable changes to the project MUST be documented in a readme.txt in the CHANGELOG section.

The format SHOULD be based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

### Semantic Versioning

The project MUST adhere to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### The assets

The `assets` folder will hold the JSs and CSSs files compiles and minified. The folder `sass` is not included in the releases. It is only for development.

The file `webpack.mix.js` have a default configuration to process them and watch for changes. More at [Laravel Mix](https://laravel-mix.com/).

## Contributors
* @scalater
* @gfirem
* @ypguerra80

## License

This project is licensed under the GPLv2 or later license - see the [license.txt](LICENSE) file for details.
