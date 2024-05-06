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

## Designing Response Payloads
In order to adhere to the [JSON API Specification](https://jsonapi.org/), we can create a resource and 
build out our structure there. See resources/V1/TicketResource
In Postman I have set the Accept header to `application/vnd.api+json` which is required for JSON:API as the
media type.
The Laravel resource automatically wraps our data in the data wrapper so we don't have to worry about
including that in the returned array.

I'm not sure yet if it is correct but to get rid of squigglies (property accessed via magic method) from the IDE
we can add @property doc comments to the resource class.

Resources are great for both api and web responses because they give us a place to include/omit properties
from the response data. User data is a good example of where you don't want to provide any data other than what
is needed to the front end (for security and data privacy)

## Optional Parameters to Load Optional Data
The client (meaning the developer using our API) may not want certain optional data because it 
increases the size of the payload. When developing apps to use our API it is important to keep
things like this in mind. Designing and developing an API requires a change in the mindset in
this way. Our end user, client, customer is a developer, not the average home user.

With that said, the TicketResource was using the 'includes' property to include user data along
with ticket data. This needs to be opt-in because the developer may not want to include user
information in some scenarios.

See ApiController. This could have been a trait or a base controller.
It can easily be switched over to a trait if that becomes a better way. 
One might argue that we don't want all of our controllers having access 
to the include() method for no reason.

So the method is now available to TicketController and the TicketResource
conditionally returns 'includes' only if the user is loaded, via the
Laravel provided method whenLoaded().

Next we optionally load user tickets for the user endpoint. As is, it is
also loading the relationship with every ticket which is the user data. 
Since we already have the user data we need to think about how best to
exclude this extra data when loading tickets with the user. Leaving it 
as is for now.

One more thing we did is to add/modify the 'links' properties in both
TicketResource and UserResource to be consistent with the way Laravel
presents pagination links.

Note: request()->get() causes IDE squigglies stating that there are
unhandled exceptions thrown. But it does not throw an exception if the 
query parameter 'include' is missing so it might be an IDE problem. 
However, since we are getting a query string parameter I feel the more
appropriate method is request()->query('include'). This method does
not cause squigglies in the IDE either. So I changed it to use query
in the ApiController.
