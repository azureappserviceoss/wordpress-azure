[![Deploy to Azure](http://azuredeploy.net/deploybutton.png)](https://azuredeploy.net/). 


# Wordpress for Azure App Services (Windows)

- This branch will be cloned when you create using WordPress (App Service) from the Azure Marketplace.
- Current WordPress version: 5.8.1 [(offical zip)](https://wordpress.org/wordpress-5.8.1.zip)
- PHP 7.4 version is being used for WordPress on the Azure Marketplace.

- For configuring database, please add the database connection string to App Service configuration.
- By default SSL is enabled for MYSQL database connections. You can add a new App setting **DB_SSL_CONNECTION** and set it to false to disable the SSL option.

  [More Details](https://wordpress.org/) on WordPress.
