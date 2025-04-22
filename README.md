![https://www.drsoft.fr](logo.png)

# drSoft.fr Hook Manager Module

## Overview

The **drSoft.fr Hook Manager** module is a hook management tool for PrestaShop. This module allows you to create, view,
and delete hooks within your PrestaShop store, offering great flexibility to customize your site's behavior.

## Features

- **Hook visualization**: View the complete list of hooks available in your PrestaShop installation
- **Custom hook creation**: Easily add new hooks tailored to your specific needs
- **Hook deletion**: Remove unused or obsolete hooks
- **Intuitive interface**: Simplified management via the PrestaShop back office

## Installation

### Option 1: Via GitHub

1. Download the latest version of the module from our GitHub
   repository: [https://github.com/drsoft-fr/drsoftfrhookmanager](https://github.com/drsoft-fr/drsoftfrhookmanager)
2. Unzip the archive and rename the folder to `drsoftfrhookmanager`
3. Copy this folder to the `/modules` directory of your PrestaShop installation
4. Go to your store's back office, section "Modules > Module Manager"
5. Search for "drSoft.fr Hook Manager" and click "Install"

### Option 2: Via Terminal (Git)

If you have access to your server's terminal and Git is installed:

```bash
$ cd {PRESTASHOP_FOLDER}/modules
$ git clone git@github.com:drsoft-fr/drsoftfrhookmanager.git
$ cd {PRESTASHOP_FOLDER}
$ php ./bin/console prestashop:module install drsoftfrhookmanager
```

## Configuration

After installation, access the module configuration via the menu "Modules > Module Manager > drSoft.fr Hook Manager >
Configure".

The configuration page allows you to:

- View all existing hooks
- Add new hooks
- Delete hooks

## Requirements

- PrestaShop 1.7.x or higher
- PHP 7.2 or higher

## Support

If you encounter problems or have questions:

1. Check the Issues section on
   GitHub: [https://github.com/drsoft-fr/drsoftfrhookmanager/issues](https://github.com/drsoft-fr/drsoftfrhookmanager/issues)
2. Create a new Issue if your problem is not already referenced

## License

This module is distributed under the MIT open source license. This means you are free to use, modify, and redistribute
it according to the terms of this license.

For more information, please see the LICENSE file included with this module or
visit [https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT).

## Links

- [drSoft.fr on GitHub](https://github.com/drsoft-fr)
- [GitHub](https://github.com/drsoft-fr/drsoftfrhookmanager)
- [Issues](https://github.com/drsoft-fr/drsoftfrhookmanager/issues)
- [www.drsoft.fr](https://www.drsoft.fr)

## Author

**Dylan Ramos** - [on GitHub](https://github.com/dylan-ramos)
