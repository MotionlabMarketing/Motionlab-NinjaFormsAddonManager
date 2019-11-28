# Ninja Forms - Pro

A companion to Ninja Forms for remote plugin (add-on) installation.

## Features
The main features of this plugin are the OAuth connection and WebHooks between the NinjaForms.com service and the distributed client sites.

Read more about [WebHooks](lib/webhooks/README.md).

## Plugin File Organization

### WebHooks
WebHook controllers are stored in the `includes/webhooks/` folder, which are registered via [config/webhooks.php](config/webhooks.php).

### Public Assets
Public "assets" ( scripts, styles, images, etc) are stored in the `public/{type}/` directory. This serves as the public facing folder for serving assets. Raw asset files are stored in `resources/assets/{type}/` for development, which are then processed into the `public/{type}` folder for deployment.

### Templating and View files
View files (aka templates) are stored in the `resources/views/` directory, which can be accessed using the `::view()` method of the plugin's API - passing the relative file path (with the file extension).

Related view files are organized inside nested folders, as opposed to using prefixed filenames.

### Configuration
Configuration files are stored in the `config/` directory, which can be accessed using the `::config()` method of the plugin's API - passing the file name (without the file extension).

While these files usually contain static values, "dynamic" values can be used by adding php login directly inside of the configuration file.

### Application Specific Libraries

In an attempt to separate unique "domain logic" from "application specific logic", common/shared functionality has been extracted into the `lib/` directory as libraries. These libraries include abstractions for WordPress related integrations, which are not unique to the problem that the plugin attempts to solve. Hopefully, this works to "hide the plumbing" so that development can focus on features.
