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

## Versioning the API
The most straight forward thing and what we are doing in this course is to add a version parameter to the URL.
`http://tickets-please.test/api/v1/tickets`

But also look at Modular Laravel by Mateus Guimarães on Laracasts.

Using apiResource instead of resource in the routes file will omit unnecessary routes such as create and edit which
would show forms in a web application. Thanks Laravel :-)

Starting to go off track a little here because I'm using Laravel 11 and the course is on version 10.
The new api_v1 routes file is loaded in bootstrap/app in the 'then' property
