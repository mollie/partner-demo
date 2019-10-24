# Partner demo

This is a dummy platform to be used as an example of the Mollie's Hosted Onboarding.
The purpose is showing how it has been integrated, and making it possible to run as demo app.

Mollie's Hosted Onboarding is a way to seemlessly onboard your customers at Mollie to do payments.

### Running the development environment

Trigger the console to see valid commands
```shell script
./console
```

Start dev environment
```shell script
./console upd # run environment detached
# or
./console up # run environment attached (tail -f php and mysql logs)
```

Connect to container (`fpm-php` default)
```shell script
./console bash
```

Now that you have the environment up and running you can stop by:
 - Pressing keys `ctrl+c` when attached (`./console up`), then
 - `./console down`

### The hosted onboarding flow described

The platform uses Laravel as a framework and is using the Mollie API's combined to make a seemless onboarding flow for 
it's customers. We allow them to register for the platform using the basic Laravel auth.

Then we start by gaining authorization over their Mollie account.

#### OAuth2 authorization

When we spot we don't have an access token for the customer yet, we start the OAuth authorization flow.

We implemented the Mollie Connect flow using the oAuth2 package: https://github.com/mollie/oauth2-mollie-php

A authorize URL is generated using the package and customers can click a button to start connecting to their Mollie 
account. As a change on the normal flow, customers will first be offered a welcome page at Mollie where a logo that the
platform configured is shown. After that they can either login to, or register a Mollie account.

Customers can then give authorization on a Mollie screen where after they will be sent back to the platform with the 
authorization code.

We use this code to generate an access token and refresh toke using the OAuth2 mollie package. And store these in the
platforms database.

Before every oauth request we make sure we check the authorization expiry and refresh the token when needed.

#### After successful authorization

When the customer has given consent for access we return on the OAuth return controller and there is 2 possible 
scenario's.

We spot these 2 scenario's using the Onboarding status API in the mollie-api-php package:
https://docs.mollie.com/reference/v2/onboarding-api/get-onboarding-status

##### Mollie still needs data for onboarding
When the merchant has logged in to an existing account we can see if Mollie still need data and the merchant does not 
have payments enabled yet.

When that is the case we use the Submit Onboarding data API to give Mollie the data that we 
already have within the platform to prefill some fields in the Mollie onboarding.

After that we send them immediately to the dashboard link provided in the Status API to complete the boarding.

##### Mollie does not need data (they've logged into an existing account).
When the Status API reveals that we don't need data anymore and payments are already enabled, we send them to the status
page in the next chapter.

#### Status page
When the merchant is within the boarding they can at all times return back to the platforms OAuth redirect URL.
So we need the return controller to check if we have an access token for the merchant and show a view with the current
status.

We use the Get Onboarding Status API (https://docs.mollie.com/reference/v2/onboarding-api/get-onboarding-status) from 
Mollie to check 3 things: the status (needs-data, in-review or completed), canReceivePayments and canReceiveSettlements.

This application showcases the possible combinations that we can get and what that means. What kind of copy do we show 
the merchant on our platform and should we show a button to complete the onboarding.

<implement references to the status object and view switching here>

#### Add a section here to talk about the profiles and methods API here
