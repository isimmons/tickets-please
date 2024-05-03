# Tickets Please
Tickets Please is the project for Jeremy McPeak's "Laravel API Master Class" on laracasts.com

Tickets Please is a support ticket tracking and resolution backend API upon which developers can build their apps.

## Designing the URL
The base url is http://tickets-please.test/api because this is in development.
Ideally it will be something like https://ticketsplease.com/api in production
Following are the end points we need for developers to be able to make good use of the API

- tickets
- users
- more to come...

## Documenting the API
This is going to require some documentation so we know what some data returned from the API means.
I personally would just give data meaningful names but I'll stick close to the structure provided by the course for now.

Status Codes for tickets:
A = active
C = completed
H = hold
X = canceled

## Laravel API Setup
In a default Laravel app with no starter kit which is what we started with, we need to run `artisan install:api`.
This will setup and run the personal_access_tokens migration and add the routes/api.php file. We need to manually
add the HasApiTokens trait to the User model in order to use the token methods described later.

## Versioning the API
The most straight forward thing and what we are doing in this course is to add a version parameter to the URL.
`http://tickets-please.test/api/v1/tickets`

But also look at Modular Laravel by Mateus Guimarães on Laracasts.

Using apiResource instead of resource in the routes file will omit unnecessary routes such as create and edit which
would show forms in a web application. Thanks Laravel :-)

Starting to go off track a little here because I'm using Laravel 11 and the course is on version 10.
The new api_v1 routes file is loaded in bootstrap/app in the 'then' property

## Auth Tokens
See AuthController::login to see how to create and assign a token to an authenticated user.

For revoking tokens there are multiple methods.

`$request->user()->tokens()->delete()`
We don't want to do this unless a user is being completely banned for some reason along with any and all apps they may
have created using their personal access tokens. This method will delete all tokens and break any app using them.

`$request->user()->tokens()->where('id', $tokenId)->delete()`
This is ok in cases where we have the specific token id

`$request->user()->currentAccessToken()->delete()`
This will get and delete the current token used for this request. So this is how to sign the user out in the logout method

For the purpose of learning without distraction, the additional parameters are added to the createToken method to
give all abilities and expire the token after a month. This will be changed. Expiration should be shorter. A day or 
30 minutes for example, depending on the real world use case for the API
There is also a app wide config setting in config/sanctum.php that is set to null by default. you can enter
a numeric value (minutes) here and it will override any tokens expires_at field in the database.
[token expiration docs](https://laravel.com/docs/11.x/sanctum#token-expiration)
