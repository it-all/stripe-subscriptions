<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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

    [
        'name' => 'Plan 4',
        'price' => 50,
        'frequency' => 'month',
        'perks' => [
            'Everything in Plan 3 +',
            'Something else 3',
        ],
        'stripeId' => $_ENV['STRIPE_PLAN4_ID_LIVE'],
        'stripeTestId' => $_ENV['STRIPE_PLAN4_ID_TEST'],
    ],

    [
        'name' => 'Plan 5',
        'price' => 100,
        'frequency' => 'month',
        'perks' => [
            'Everything in Plan 4 +',
            'Something else 4',
        ],
        'stripeId' => $_ENV['STRIPE_PLAN5_ID_LIVE'],
        'stripeTestId' => $_ENV['STRIPE_PLAN5_ID_TEST'],
    ],

    [
        'name' => 'Plan 6',
        'price' => 250,
        'frequency' => 'month',
        'perks' => [
            'Everything in Plan 5 +',
            'Something else 5',
        ],
        'stripeId' => $_ENV['STRIPE_PLAN6_ID_LIVE'],
        'stripeTestId' => $_ENV['STRIPE_PLAN6_ID_TEST'],
    ],

    [
        'name' => 'Plan 7',
        'price' => 500,
        'frequency' => 'month',
        'perks' => [
            'Everything in Plan 6 +',
            'Something else 6',
        ],
        'stripeId' => $_ENV['STRIPE_PLAN7_ID_LIVE'],
        'stripeTestId' => $_ENV['STRIPE_PLAN7_ID_TEST'],
    ],

    [
        'name' => 'Plan 8',
        'price' => 1000,
        'frequency' => 'month',
        'perks' => [
            'Everything in Plan 7 +',
            'Something else 7',
        ],
        'stripeId' => $_ENV['STRIPE_PLAN8_ID_LIVE'],
        'stripeTestId' => $_ENV['STRIPE_PLAN8_ID_TEST'],
    ],
];
/** end config */

$apiKey = $testing ? $_ENV['STRIPE_PUBLISHABLE_API_KEY_TEST'] : $_ENV['STRIPE_PUBLISHABLE_API_KEY_LIVE'];

$page = $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
$successUrlPage =  "$page?success";
$canceledUrlPage = "$page?canceled";

$successDiv = '';
$canceledDiv = '';
if (isset($_GET['success'])) {
    $successDiv = getSuccessDiv();
} elseif (isset($_GET['canceled'])) {
    $canceledDiv = getCanceledDiv();
}

$plansHtml = getPlansHtml($plans, $testing);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Stripe Subscriptions</title>
        <script src="https://js.stripe.com/v3"></script>
        <style type="text/css">
            #main {
                width: 100%;
                padding: 30px 0;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                align-content: space-around;
            }
            #success, #canceled {
                width: 550px;
                margin: 0 auto;
                border: 3px solid;
                text-align: center;
            }
            #success {
                border-color: green;
            }
            #canceled {
                border-color: red;
            }
            #primary-header {
                text-align: center;
                font-size: 1.8em;
            }
            #description {
                width: 540px;
                margin: 0 auto;
                text-align: left;
            }
            #description-subhead {
                text-align: center;
                font-size: 1.2em;
                font-weight: bold;
            }
            #description-text {
                font-size: 1.1em;
                line-height: 1.2em;
            }
            .plan {
                margin: 10px auto;
                padding: 22px;
                width: 300px;
                border: 1px solid grey;
                flex-grow: 1;
                font-weight: bold;
            }
            .checkout-button {
                background-color: #123456;
                color: #FFF;
                padding: 8px 12px;
                border: 0;
                border-radius: 4px;
                font-size:1em;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <header>
            <?= $successDiv ?>
            <?= $canceledDiv ?>
            <div id="error-message"></div>
            <h2 id="primary-header">Please Support Us by Subscribing</h2>
            <div id="description">
                <h3 id="description-subhead">Your contribution supports our work.</h3>
                <p id="description-text">Thank you very much for your consideration. Your support enables us to continue our work on this project. Please choose a plan below:</p>
            </div>
        </header>
        <main>
            <div id="plans"><?= $plansHtml ?></div>
        </main>

        <script>
            (function() {
                var stripe = Stripe('<?= $apiKey ?>');
                document.addEventListener('click', function (event) {
                    if (event.target.matches('.checkout-button')) {
                        // When the customer clicks on the button, redirect
                        // them to Checkout.
                        stripe.redirectToCheckout({
                        items: [{plan: event.target.id, quantity: 1}],

                        // Do not rely on the redirect to the successUrl for fulfilling
                        // purchases, customers may not always reach the success_url after
                        // a successful payment.
                        // Instead use one of the strategies described in
                        // https://stripe.com/docs/payments/checkout/fulfillment
                        successUrl: window.location.protocol + '//<?= $successUrlPage ?>',
                        cancelUrl: window.location.protocol + '//<?= $canceledUrlPage ?>',
                        })
                        .then(function (result) {
                        if (result.error) {
                            // If `redirectToCheckout` fails due to a browser or network
                            // error, display the localized error message to your customer.
                            var displayError = document.getElementById('error-message');
                            displayError.textContent = result.error.message;
                        }
                        });
                    }
                }, false);
            })();
        </script>
    </body>
</html>
<?php
// functions

function getPlansHtml(array $plans, bool $isTest = false): string 
{
    $plansHtml = '';
    for ($i = 0; $i <= count($plans) - 1; ++$i) {
        $plansHtml .= getPlanHtml($plans[$i], $isTest);
    }
    return $plansHtml;
}

function getPlanHtml(array $plan, bool $isTest = false): string 
{
    $name = $plan['name'];
    $price = $plan['price'];
    $frequency = $plan['frequency'];
    $perks = implode("<br>", $plan['perks']); /** use getPerks($plan['perks']); for customization */
    $stripeId = $isTest ? $plan['stripeTestId'] : $plan['stripeId'];
    if (mb_strlen($stripeId) == 0) {
        return '';
    }

    return <<<EOT
<div class="plan">
    $name<br>
    $$price per $frequency<br>
    $perks<br>
    <br>
    <button id="$stripeId" class="checkout-button" role="link">Join Now</button>
</div>
EOT;
}

function getSuccessDiv(): string 
{
    return <<<EOT
<div id="success">
    <h1>Subscribed. Thank you!!</h1>
</div>
EOT;
}

function getCanceledDiv(): string 
{
    return <<<EOT
<div id="canceled">
    <h1>&raquo; Payment canceled. Please try again.</h1>
</div>
EOT;
}

/** not currently used */
function getPerks(array $perks): string 
{
    $perks = '';
    foreach ($perks as $perk) {
        $perks .= $perk . '<br>';
    }
    return $perks;
}
