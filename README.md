# farmOS Conservation Planner

Provides farmOS features for Resource Conservation Districts to facilitate and accelerate the
conservation planning processes. Please see the [User's Guide](https://docs.google.com/document/d/16iH6pDua5P8h9jwwMcFFI97ApQfVMSo4AjvnXbCmC7Q/edit?tab=t.0#heading=h.xhitsmsyplk) for more information on how it works and what it can do for your RCD.

This module is an add-on for the [farmOS](http://drupal.org/project/farm)
Drupal distribution.

Note that this module makes some opinionated changes to the default farmOS data
model and UI with the intention of creating a system for managing records and
workflows that are specific to Resource Conservation Districts. Therefore,
installing this module in a farmOS instance used for other purposes is not
recommended. This may change in the future if there is demand and sponsorship
for separating the features and overrides.

Refer to the included CHANGELOG.md for an overview of all the additions,
changes, and overrides this module makes to the default farmOS data model and
UI.

## Email setup

This module depends on the
[Symfony Mailer Lite](https://drupal.org/project/symfony_mailer_lite) module
for sending email attachments. A default SMTP email transport is automatically
configured for relaying emails through an existing SMTP server. To use this,
add the following lines to `settings.php` to specify your SMTP credentials:

```php
$config['symfony_mailer_lite.symfony_mailer_lite_transport.smtp']['configuration']['user'] = 'my-username';
$config['symfony_mailer_lite.symfony_mailer_lite_transport.smtp']['configuration']['pass'] = 'my-password';
$config['symfony_mailer_lite.symfony_mailer_lite_transport.smtp']['configuration']['host'] = 'smtp.example.com';
$config['symfony_mailer_lite.symfony_mailer_lite_transport.smtp']['configuration']['port'] = '587';
```

If a different type of transport is required, then you will need to manually
configure it in `/admin/config/system/symfony-mailer-lite/transport`.

## Maintainers

Current maintainers:

- Michael Stenta (mstenta) - https://github.com/mstenta

This project has been supported by:

- [University of California Research Grants Program Office (UC-RGPO)](https://uckeepresearching.org/california-climate-action/), Climate Action Seed Grant R02CP7344
- [Upper Salinas-Las Tablas Resource Conservation District](https://www.us-ltrcd.org/)
- [Coastal San Luis Resource Conservation District](https://www.coastalrcd.org/)
- [CalPoly Initiative for Climate Leadership and Resilience](https://climate.calpoly.edu/)
- [Point Blue Conservation Science](https://www.pointblue.org/)
- [Farmier](https://farmier.com/)
