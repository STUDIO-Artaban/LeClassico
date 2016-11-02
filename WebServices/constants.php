<?php

////// Web service error codes
define("WEBSERVICE_ERROR_SERVER_UNAVAILABLE", 1);

// Connection
define("WEBSERVICE_ERROR_LOGIN_FAILED", 2);
define("WEBSERVICE_ERROR_TOKEN_EXPIRED", 3);
define("WEBSERVICE_ERROR_INVALID_LOGIN", 4);
define("WEBSERVICE_ERROR_SYSTEM_DATE", 5);

define("WEBSERVICE_ERROR_INVALID_TOKEN", 6);
define("WEBSERVICE_ERROR_INVALID_USER", 7);
define("WEBSERVICE_ERROR_INVALID_OPERATION", 8);

// Publications
define("WEBSERVICE_ERROR_INVALID_PUBLICATION_ID", 9);
define("WEBSERVICE_ERROR_REQUEST_PUBLICATION_DELETE", 10);
define("WEBSERVICE_ERROR_REQUEST_PUBLICATION_INSERT", 11);

// Comments
define("WEBSERVICE_ERROR_REQUEST_COMMENT_DELETE", 12);
define("WEBSERVICE_ERROR_REQUEST_COMMENT_INSERT", 13);

?>
