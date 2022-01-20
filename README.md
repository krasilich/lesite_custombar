#LeSite Custom Bar

Module adds simple message on top of all pages.

## Installation

- Add this repository to your ```composer.json``` ```https://github.com/krasilich/lesite_custombar.git```
- Run ```composer require krasilich/lesite_cunstombar:0.1.0```

Example:
```json
{
    ...
    "repositories": [
        ...
        {
            "url": "https://github.com/krasilich/lesite_custombar.git",
            "type": "git"
        }
        ...
    ],
    ...
}
```

- Run ```bin/magento module:enable LeSite_CustomBar```
- Run your magento deployment procedure (setup:upgrade, setup:di:compile, etc)

## Configurtion
- In Magento admin go to Stores > Configuration > LeSite > Custom Bar > Settings
- Select Customer groups for which you would like to show the message
- If no customer groups selected - message will not be shown to any customer

## Known Issues
- If you change the customer groups in module configs you have to refresh FPC
- If customer changes its group - it should relogin to apply changes
