# Live Journey Stacker

**Live Journey Stacker**: a real-time view of multi-leg UK public transport 
journeys.

This is a project in very early development which aims to provide a simple tool
to allow planning and real-time status monitoring of multi-leg public transport
journeys in the UK.

## Problem & vision ##

When travelling on a journey with more than a couple of legs (trains, buses 
etc.), it can be difficult to track both the sequence of legs as well as the 
live status of the transport. This often involves having a list of all the 
legs then using different apps/websites to track each leg's status.

Typical scenarios of this are:

* "Single" journeys comprising of multiple legs (e.g. you need to get a bus, a
  couple of trains, then another bus to get to your destination).
* Visiting multiple locations in one day, when you need to know how much time
  you can have at each location.
* Transport challenges, for example attempting to visit multiple stations in a
  set period of time.

In both these cases knowing if you are likely to make connections can be crucial
to success.

The vision is a simple, mobile-first, web app which allows adding multiple
legs (including on-the-fly during the journey) and seeing the real-time status
information all in one place.

## Current status ##

Has the basic ability to create journeys, but has no search capability.

To add a journey leg, the following are required from RealTimeTrains:
* Service UID: found in URL of the service on RTT. 6 digit code after `gb-nr:`
  e.g. `A12345`.
* Date: needs to match the date shown in RTT URL. This may be different to
  what you might expect for trains around midnight.

Currently not hosted on the Internet.

Current code can be run with Docker Compose:

1.  Install Docker including Docker Compose.
2.  Obtain a RealTimeTrains API key from https://api.rtt.io/
3.  Clone the repo.
4.  In `.env`, update `MY_UID` to the output of `id -u` and `MY_GID` to the
    output of `id -g`.
5.  In the `src/config` folder, copy `creds.php.m4` to `creds.php`, then add 
    your API credentials to the parameters.
6.  Start Docker Compose by running `docker compose up` in the root repo 
    directory, then visiting http://localhost:8000.

## Roadmap ##

The first iteration will focus on UK mainline trains. This wil be published as a
publically-usable website.

Future features will be driven by user interest & needs (including my own), and 
time availability. Buses may be next, but real-time information is harder to come 
by.

It is very unlikely anything outside UK will be added.

## Design decisions ##

* Mobile first: should work very nicely on small screens as well as larger ones.
* Lightweight: no unnecessary javascript libraries, graphics, etc.
* Clean design: no unnecessary clutter.
* Use of progressive enhancement: the app should work over and above modern
  features.

All of these will help in the expected sitation of using the app "on the go"
where smaller devices are typically used and Internet connection may be patchy 
or slow.

Also, I would like to avoid cookies and other local storage as much as possible. 
Currently, no cookeis are used. If some kind of login is eventually required, then 
basic functional cookies will become necessary.

If the app becomes popular and needs special hosting of its own, ads and/or
subscription may be necessary. Only "well behaved" ads will be used, e.g.
a small one at the bottom of the screen.

## Copyright, data sources & licensing ##

Copyright &copy; 2025 Ezra Morris

Licensed under [MIT License][1]

Uses data from [Realtime Trains][2]

Uses [Smarty][3], licenced under [GNU Lesser General Public License][4], which references [GNU GPL][5]


[1]: LICENSE
[2]: http://www.realtimetrains.co.uk/
[3]: https://github.com/smarty-php/smarty/tree/master
[4]: https://github.com/smarty-php/smarty/blob/master/LICENSE
[5]: https://www.gnu.org/licenses/gpl-3.0.en.html

