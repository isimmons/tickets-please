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
