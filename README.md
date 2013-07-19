Example
```php
<?php
include 'MsnLiveOAuth.php';

$msnAuth = new MsnLiveOAuth('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET');
if (!isset($_GET['code'])) {
    $_SESSION['csrf_livel_code'] = sha1(microtime());
    $scope = 'wl.signin wl.basic wl.contacts_emails';
    $msnAuth->GetAccessCode($scope, 'code', $_SESSION['csrf_livel_code']);
} else {
    if (isset($_SESSION['csrf_livel_code']) 
                            && $_GET['state'] == $_SESSION['csrf_livel_code']) {
        $accessInfo = $msnAuth->ValidateCodeAndGetTokenAccess($_GET['code']);
        if (isset($accessInfo['access_token'])) {
           $contacts = $msnAuth->getContacts($accessInfo['access_token']);
           print_r($contacts);
        } else {
            echo "Unahutorized";
        }
    } else {
        echo "Unahutorized";
    }
}
```
