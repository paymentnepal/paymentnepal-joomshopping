## paymentnepal-joomshopping  
This plugin was developed for Joomla CMS and JoomShopping component. Before install make sure you have one of next Joomla/JoomShopping versions:  
* Joomla 2.5.x + JoomShopping 3.x.x  
* Joomla 3.x.x + JoomShopping 4.x.x  
The plugin was tested on:  
* Joomla 2.5.14 + JoomShopping 3.15.3
* Joomla 3.1.5 + JoomShopping 4.3.3

Inside your Paymentnepal merchant area you need to fill in next fields in service settings:
3. "Handler URL:" -   http://your.site/index.php?option=com_jshopping&controller=checkout&task=step7&js_paymentclass=pm_rficb&act=notify&nolang=1;  
4. "Success URL:" -   http://your.site/index.php?option=com_jshopping&controller=checkout&task=step7&js_paymentclass=pm_rficb&act=return;  
5. "Error URL:" - http://your.site/index.php?option=com_jshopping&controller=checkout&task=step7&js_paymentclass=pm_rficb&act=cancel.  

Installing the plugin:  
1. Go to your site administrator panel (http://your.site/administator)  
2. Then go to Components -> JoomShopping -> Install&Update  
3. Choose Upload then select plugin archive from your local computer (paymentnepal_joomshopping_module.zip) and click "Upload"  
4. After that go to Components -> JoomShopping -> Options -> Payment methods, click Paymentnepal and go to Configuration page inside plugin settings. You need to fill in next fields: secret key, payment key. You can obtain values from your service settings inside Paymentnepal merchant area  
-"Order status for successful transactions": "Paid" is recommended   
-"Order status for unfinished transactions": "Pending" is recommended, should be similar to defualt status in Components -> JoomShopping -> Settings -> Order -> Default order status  
-"Order status for error transactions": choose "Cancelled" if you need to cancel order or "Pending" if you want to allow to repeat payment   
5. After making settings at "Configuration" page, go to "Main" page and select "Publish" checkbox, so payment method will be available at the checkout  
6. Click "Save"
