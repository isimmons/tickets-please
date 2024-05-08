# Tickets Please
Tickets Please is the project for Jeremy McPeak's "Laravel API Master Class" on laracasts.com

Tickets Please is a support ticket tracking and resolution backend API upon which developers can build their apps.

# BIG TODO
I think this project will be a perfect opportunity to gain knowledge and practice in several other areas.
So here is the big todo plan after finishing the course.
1. Complete rewrite of these notes so it makes more sense to others. Not docs but lesson notes
2. Write actual Docs for the API
3. Add tests
4. Refactor, add change logs, update docs
5. Regarding #4, breaking changes are new versions, non breaking as minor versions, how to properly release
    major versions vs minor versions and document upgrade paths for major versions.

4 and 5 scare me a little.

## Testing
Testing is not part of this course but I want to add it later. For a starting point on Pest and JSON:API
endpoint testing [look here](https://laravel-news.com/testing-json-api-endpoints-with-pestphp)
That is assuming you are familiar with Pest. I just need to see the difference between testing application
endpoints and testing JSON:API endpoints and I think I can get it figured out from there.

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

## Filters for Query Parameters
For the first filter, 'status' I added strtoupper in the filter because we used upper case
in the database. This way the user can type 'c' or 'C' for the status. Too much flexibility
in an API could cause complexity or even break things but I think this is simple enough to
allow it. 

Next we setup filters for title, dates, date ranges, and multiple status.
Example here is tickets including the author with status C or X with a title
that has 'foo' in it and a createdAt date within the supplied range.
`http://tickets-please.test/api/v1/tickets?include=author&filter[status]=C,X&filter[title]=*foo*&filter[createdAt]=2024-03-04,2024-05-03`

Side Note about dates, sqlite, and Jetbrains IDEs:
They will screw up the date and break your application if you do not set
your source driver up correctly for sqlite. 
date_class = TEXT
date_string_format = yyyy-MM-dd HH:mm:ss
[see jb issue here](https://intellij-support.jetbrains.com/hc/en-us/community/posts/115000338470-Default-date-format)

## Filtering Nested Resources
First commit for this section we changed a few things to make the distinction between users and authors.

Next we create a nested controller AuthorTicketsController to handle getting tickets belonging to a particular
author `http://tickets-please.test/api/v1/authors/1/tickets`
And we can still use our filters for the tickets.

## Sorting Data
First we get the User model and Author controller up to date on filtering.
`http://tickets-please.test/api/v1/authors?filter[id]=1,5,11` to filter authors by id
Of course the original url for a single author `authors/id` still works. This just gives us a way to get
multiple authors by id.

Now we will set up sorting such as
`http://tickets-please.test/api/v1/tickets?sort=title,status`
Defaults to ASC or prefixed with '-' for DEC
`http://tickets-please.test/api/v1/tickets?sort=-title,-status`

## Creating Resources with Post Requests
Remember we are using the sanctum middleware on our routes. This means in the authorise method of our request
classes we can simply return true. In other applications this would be where we make sure the user is logged
in but in this case they can't get past the route without being logged in first.

This is also where we would check roles at a later point to determine if a logged in user is able to make
a specific request but for now, any logged in user can perform any of our POST requests.

Validation notes:
Under the hood, Laravel uses PHP methods so this is not necessarily a fault of Laravel but in order to 
cover ALL edge cases for the id (non negative, non zero, non decimal, integer type, non boolean) we have to combine
a few rules. I think it's integer that allows true/false and numeric that allows decimal? Google it though.
It's true. Also waiting to see where we go with this but I think we need to check the id exists in the db
unless the act of creating a ticket triggers, creating an account or something. Not sure why it's being left 
out as a validation rule. Will revisit.
`'data.relationships.author.data.id' => ['required', 'integer', 'numeric', 'min:1'],`
Comments in the lesson suggest using the validation check for exists and changes will come in future lessons
but for now, we have complete control over the returned response by checking it in the controller in a
try/catch.
We are returning a 200 response as a sort of security through obscurity because attackers tend to use
automated tools that search for error statuses. It is debatable whether to do this or not. Another thing
to consider, research, and revisit. Also look at AuthController where we used error instead of success message.

We make it possible to create a new ticket both from the TicketController and from the AuthorTicketController.
Remember this when documenting the API. 2 ways to create tickets.

## Deleting with a Delete Request
In the case of tickets we go ahead and return a 404 error since this is the expected behavior in a typical
system. We aren't trying to hide any implementation details from hackers here.

We provide the ability to delete a ticket in both the TicketController and the AuthorTicketController
See @Bionik6 APIExceptions class in [lesson comments](https://laracasts.com/series/laravel-api-master-class/episodes/14)
I'm sure I'll be looking into this change which will change how and where I use try/catch blocks
in the controllers.

## Replace a Resource with PUT
My first question is always "why would I want to replace it?"

To distinguish between PATCH/PUT requests we create an actual replace() method instead of running them
both through the update method.

Replacing the created_at/updated_at fields is something to consider. But in this case we are not.
Had to reverse how to show the description field in TicketResource because it wasn't showing for the replace method.
Now we only stop it from showing on the 2 routes where we don't want it to show.
`!$request->routeIs(['tickets.index', 'authors.tickets.index']),`

# TODO Security
roles, policies, etc. At the moment any user can make all of their tickets belong to other users
Several access control lessons coming up soon.

## Update Resources with a Patch Request
We implemented the ability to use a PATCH request to update tickets.
We also cleaned up by creating a BaseTicketRequest with the mappedAttributes method. In this way we not only
cleaned up those arrays of attributes in the controllers but also provided a way to do both PUT and PATCH
requests with a one-liner and determine which attributes are in the request.
