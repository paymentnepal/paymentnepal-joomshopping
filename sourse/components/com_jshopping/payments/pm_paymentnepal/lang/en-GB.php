<?php
//защита от прямого доступа
defined('_JEXEC') or die();

define ('ADMIN_CFG_PAYMENTNEPAL_SECRET_KEY', 'Secret key');
define ('ADMIN_CFG_PAYMENTNEPAL_SECRET_KEY_DESCRIPTION', 'Custom character set is used to sign messages are forwarded.');
define ('ADMIN_CFG_PAYMENTNEPAL_HIDDEN_KEY', 'Hidden key');
define ('ADMIN_CFG_PAYMENTNEPAL_HIDDEN_KEY_DESCRIPTION', 'Custom character set is used to sign hidden messages to Result URL.');
define ('ADMIN_CFG_PAYMENTNEPAL_TEST_MODE', 'Test mode');
define ('ADMIN_CFG_PAYMENTNEPAL_TEST_MODE_DESCRIPTION', '(optional) Test mode.');
define ('ADMIN_CFG_PAYMENTNEPAL_PAYMODE', 'Payment method');
define ('ADMIN_CFG_PAYMENTNEPAL_PAYMODE_DESCRIPTION', '(optional) Payment code. If set selected payment method will be chosen automatically. Available payment codes you can <a href=\"https://cp.pay2pay.com/?page=dev\" title=\"Available payment codes list\" target=\"_blank\">look under \"For developers\" tab</a> in shop control panel.');

define('PAYMENTNEPAL_REDIRECT_PENDING_STATUS_ERROR', '<p>Error! Default order status must match "order status for pending payments" in payment module settings.</p>');
define('PAYMENTNEPAL_REDIRECT_TO_PAYMENT_PAGE', '<p>Thank you for your order! Now you will be redirected to the payment page.</p>');

define('PAYMENTNEPAL_ERROR_POST_FIELDS_REQUIRED', 'POST xml and sign fields are supposed');
define('PAYMENTNEPAL_ERROR_SIGN_VALIDATION_FAILED', 'Request sign validation error');
define('PAYMENTNEPAL_ERROR_ORDER_ID_ERROR', "Couldn't get order id from request XML object");
define('PAYMENTNEPAL_ERROR_ORDER_INFORMATION_FAILED', "Couldn't get order information with requested order id");
define('PAYMENTNEPAL_ERROR_AMOUNT_CHECK_FAILED', 'Amount check error: request and order amounts are not match');
define('PAYMENTNEPAL_ERROR_CURRENCY_CHECK_FAILED', 'Currency check error: request and order currencies are not match');
define('PAYMENTNEPAL_ERROR_ORDER_PAID_ALREADY', 'Order already has been paid');
define('PAYMENTNEPAL_ERROR_UNKNOWN_STATUS_FIELD_VALUE', 'Unknown status field value in XML request');
define('PAYMENTNEPAL_UNKNOWN_ERROR', 'Unknown error during handling XML request');

define('PAYMENTNEPAL_SUCCESS_URL_REQUEST_PASSED', 'Success Url request successful');
define('PAYMENTNEPAL_SUCCESS_URL_ORDER_ALREADY_PAID', 'Order has been already paid (there was Result request before)');
define('PAYMENTNEPAL_SUCCESS_URL_ORDER_STATUS_FAILED', 'Default order status does not match to transaction pending status');
define('PAYMENTNEPAL_RESULT_URL_PAYMENT_SUCCESSFUL', 'Payment successful');
define('PAYMENTNEPAL_RESULT_URL_PAYMENT_CANCELLED', 'Payment of the order is canceled');
define('PAYMENTNEPAL_RESULT_URL_PAYMENT_PROCESS', 'Payment is expected');
define('PAYMENTNEPAL_RESULT_URL_PAYMENT_RESERVED', 'Funds are reserved');

define ('PAYMENTNEPAL_PAY', 'Pay');
