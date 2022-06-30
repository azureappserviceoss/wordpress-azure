# Wordpress for Azure App Services (Windows)

[![Deploy to Azure](https://aka.ms/deploytoazurebutton)](https://portal.azure.com/#create/Microsoft.Template/uri/https%3A%2F%2Fraw.githubusercontent.com%2Fazureappserviceoss%2Fwordpress-azure%2Fmaster%2Fazuredeploy.json)

- This branch will be cloned when you create using WordPress (App Service) from the Azure Marketplace.
- Current WordPress version: 5.8.1 [(official zip)](https://wordpress.org/wordpress-5.8.1.zip)
- PHP 7.4 version is being used for WordPress on the Azure Marketplace.

- For configuring database, please add the database connection string to App Service configuration.
- By default SSL is enabled for MYSQL database connections. You can add a new App setting **DB_SSL_CONNECTION** and set it to false to disable the SSL option.

## Template Parameters

- Resource Group - Select a resource group for the template resources
- Region - Resource group region, automatically selected when selecting the resource group
- Site Name - Globally unique name of site, final site URL will be [Site Name]+.azurewebsites.net
- Hosting Plan Name - App Service Plan name
- Site Location - Region for the site
- Sku - Free, Basic or Standard
- Repo Url - Url of this Wordpress for Azure App Services repo (https://github.com/azureappserviceoss/wordpress-azure) or another repo
- Branch - Branch name of above repo

## Overview of WordPress

WordPress is a free and open source content management system (CMS) based on PHP and MySQL. It is the most widely used CMS software in the world, and as of June 2021, it powers more than 40% of the top 10 million websites and has an estimated 64% market share of all websites built using a CMS.

WordPress started as a simple blogging system in 2003, but it has evolved into a full CMS with thousands of plugins, widgets, and themes. It is licensed under the General Public License (GPLv2 or later).

[More Details](https://wordpress.org/) on WordPress.
