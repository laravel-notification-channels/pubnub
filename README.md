# PubNub Notifications Channel for Laravel 5.3 [WIP]
[![Latest Version on Packagist](https://img.shields.io/packagist/v/laravel-notification-channels/pubnub.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/pubnub)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/laravel-notification-channels/pubnub/master.svg?style=flat-square)](https://travis-ci.org/laravel-notification-channels/pubnub)
[![StyleCI](https://styleci.io/repos/65854225/shield)](https://styleci.io/repos/65854225)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/:sensio_labs_id.svg?style=flat-square)](https://insight.sensiolabs.com/projects/:sensio_labs_id)
[![Quality Score](https://img.shields.io/scrutinizer/g/laravel-notification-channels/pubnub.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/pubnub)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/laravel-notification-channels/pubnub/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/laravel-notification-channels/pubnub/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel-notification-channels/pubnub.svg?style=flat-square)](https://packagist.org/packages/laravel-notification-channels/pubnub)

PubNub Notifications Channel for Laravel 5.3. This channel allows you to send message payloads as well as push notifications to iOS, Android and Windows using PubNub.

## Contents

- [Installation](#installation)
	- [Setting up the PubNub service](#setting-up-the-PubNub-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

```bash
composer require laravel-notification-channels/pubnub
```

Add the service provider to your `config/app.php`

```php
// config/app.php
'providers' => [
    ...
    NotificationChannels\Pubnub\PubnubServiceProvider::class,
],
```

### Setting up the PubNub service

Add your PubNub Publish Key, Subscribe Key and Secret Key to your `config/services.php`

```php
// config/services.php
...

'pubnub' => [
    'publish_key'   => env('PUBNUB_PUBLISH_KEY'),
    'subscribe_key' => env('PUBNUB_SUBSCRIBE_KEY'),
    'secret_key'    => env('PUBNUB_SECRET_KEY'),
],

... 
```

## Usage

```php
use NotificationChannels\Pubnub\PubnubChannel;
use NotificationChannels\Pubnub\PubnubMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [PubnubChannel::class];
    }

    public function toPubnub($notifiable)
    {
        return (new PubnubMessage())
            ->channel('my_channel')
            ->title('My message title')
            ->body('My message body');
    }
}
```

Alternatively you may supply a channel specifically related to your notifiable by implementing the `routeNotificationForPubnub()` method.

```php
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;
    
    public function routeNotificationForPubnub()
    {
        return $this->pubnub_channel;
    }
}
```

Sending a push notification. You may chain any of the `withiOS()`, `withAndroid()` and `withWindows()` methods to add push notifications to the message with each of the platforms.

```php
use NotificationChannels\Pubnub\PubnubChannel;
use NotificationChannels\Pubnub\PubnubMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    public function via($notifiable)
    {
        return [PubnubChannel::class];
    }

    public function toPubnub($notifiable)
    {
        return (new PubnubMessage())
            ->channel('my_channel')
            ->title('Alert: Jon Doe Sent You A Message')
            ->body('Hi')
            ->withiOS(
                (new PubnubMessage())
                    ->sound('default')
                    ->badge(1)
            )
            ->withAndroid(
                (new PubnubMessage())
                    ->sound('notification')
                    ->icon('myicon')
            )
            ->withWindows(
                (new PubnubMessage())
                    ->type('toast')
                    ->delay(450);
            );
    }
}
```

### Available methods

 - `channel('')`: Specifies the channel the message should be sent to
 - `title('')`: Sets the title of the message
 - `body('')`: Sets the body of the message
 - `storeInHistory(true)`: If the message should be stored in the Pubnub history
 - `badge(1)`: Sets the number to display on the push notification's badge (iOS)
 - `sound('')`: Sets the sound for the push notification (iOS, Android)
 - `icon('')`: Sets the push notification icon (Android)
 - `type('')`: Sets the type of push notification (Windows)
 - `delay(450)`: Sets the delay in seconds for the push notification (Windows)
 - `setData($key, $value)`: Adds any extra data to the payload you may need
 - `setOption($key, $value)`: Sets any option to the push notification ([iOS][reference-ios], [Android][reference-android], Windows) 
 - `withiOS(PubnubMessage $message)`: Sets the push notification for iOS
 - `withAndroid(PubnubMessage $message)`: Sets the push notification for Android
 - `withWindows(PubnubMessage $message)`: Sets the push notification for Windows

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email wade@iwader.co.uk instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [iWader](https://github.com/iWader)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[reference-ios]: https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/TheNotificationPayload.html#//apple_ref/doc/uid/TP40008194-CH107-SW1
[reference-android]: https://developers.google.com/cloud-messaging/http-server-ref#notification-payload-support
