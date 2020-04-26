# A Simple Landing Page for Stripe Subscriptions  
This uses an array of your [Stripe](https://stripe.com) subscription plans to generate the HTML and JavaScript necessary to redirect your site visitors to the Stripe checkout upon plan selection. It also receives and displays success, cancellation, and error messages.
  
## Requirements  
PHP 7.1+ ([Nullable Types](https://www.php.net/manual/en/migration71.new-features.php#migration71.new-features.nullable-types))  
[vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) 4.1+  
  
## Usage  
Copy .env.example to .env  
Edit .env with your Stripe info  
Edit the config info in index.php with your subscription plan info, set $testing to false when ready to go live. See below:  

    /** config */
    $testing = true;

    $plans = [
        [
            'name' => 'Plan 1',
            'price' => 1,
            'frequency' => 'month',
            'perks' => [
                'A great big virtual hug',
            ],
            'stripeId' => $_ENV['STRIPE_PLAN1_ID_LIVE'],
            'stripeTestId' => $_ENV['STRIPE_PLAN1_ID_TEST'],
        ],

        [
            'name' => 'Plan 2',
            'price' => 10,
            'frequency' => 'month',
            'perks' => [
                'A great big virtual hug',
                'Something else 1',
            ],
            'stripeId' => $_ENV['STRIPE_PLAN2_ID_LIVE'],
            'stripeTestId' => $_ENV['STRIPE_PLAN2_ID_TEST'],
        ],

        [
            'name' => 'Plan 3',
            'price' => 25,
            'frequency' => 'month',
            'perks' => [
                'Everything in Plan 2 +',
                'Something else 2',
            ],
            'stripeId' => $_ENV['STRIPE_PLAN3_ID_LIVE'],
            'stripeTestId' => $_ENV['STRIPE_PLAN3_ID_TEST'],
        ],
    ];
    /** end config */

  
### Screenshot  
![Screenshot](http://it-all.com/stripe-subscriptions/scrnsht.png)  