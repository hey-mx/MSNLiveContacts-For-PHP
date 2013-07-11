Example
```php
<?php
include 'MsnLiveOAuth.php';

$msnAuth = new MsnLiveOAuth('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET');
if (!isset($_GET['code'])) {
    $scope = 'wl.signin wl.basic wl.contacts_emails';
    $msnAuth->GetAccessCode($scope, 'code');
} else {
    $accessInfo = $msnAuth->ValidateCodeAndGetTokenAccess($_GET['code']);
    if (isset($accessInfo['access_token'])) {
       $contacts = $msnAuth->getContacts($accessInfo['access_token']);
       print_r($contacts);
    } else {
        echo "Unahutorized";
    }
}
```
