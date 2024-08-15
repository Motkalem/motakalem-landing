<?php

use App\Actions\Paymob\callbackAction;
use App\Http\Controllers\MainController;
use App\Models\ParentContract;
use App\Notifications\SuccessSubscriptionPaidNotification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/store-data', function () {

    $url = "https://eu-test.oppwa.com/v1/payments";
    $data = "entityId=8ac7a4c790e4d8720190e56cfc7f014f" .
        "&amount=23" .
        "&paymentType=DB" .
        "&createRegistration=true" .
        "&merchantTransactionId=310" .
        "&currency=SAR" .
        "&testMode=EXTERNAL" .
        "&paymentBrand=MADA" .
        "&card.number=4464040000000007" .
        "&card.holder=John Smith" .
        "&card.expiryMonth=12".
        "&card.expiryYear=2024" .
        "&card.cvv=100" .
        "&customer.email=john.smith@gmail.com" .
        "&customer.givenName=Amin" .
        "&customer.ip=192.168.0.0" .
        "&customer.surname=John" .
        "&customer.language=AR" .
        "&billing.city=MyCity" .
        "&billing.country=SA" .
        "&billing.postcode=11564" .
        "&billing.state=jeda" .
        "&billing.street1=MyStreet" .
        "&standingInstruction.expiry=2030-08-11" .
        // "&customParameters[3DS2_enrolled]=true".
        "&customParameters[3DS2_flow]=challenge" .
        "&standingInstruction.mode=REPEATED" .
        "&standingInstruction.type=UNSCHEDULED" .
        "&standingInstruction.source=CIT" .
        "&standingInstruction.recurringType=SUBSCRIPTION"
        // "&threeDSecure.eci=05" .
        // "&threeDSecure.authenticationStatus=Y" .
        // "&threeDSecure.version=2.2.0"
        // "&threeDSecure.dsTransactionId=c75f23af-9454-43f6-ba17-130ed529507e" .
        // "&threeDSecure.acsTransactionId=2c42c553-176f-4f08-af6c-f9364ecbd0e8" .
        // "&threeDSecure.verificationId=MTIzNDU2Nzg5MDEyMzQ1Njc4OTA=" .
        // "&threeDSecure.amount=23" .
        // "&threeDSecure.currency=SAR" .
        // "&threeDSecure.flow=challenge"
        ;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization:Bearer OGFjN2E0Yzc5MGU0ZDg3MjAxOTBlNTZiYjRiZDAxNDZ8VGczeUNDY0RENmJlRldaOQ=='
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $responseData = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $responseData;
});


Route::get('/schedule', function () {


        $url = "https://eu-test.oppwa.com/scheduling/v1/schedules";
        $data = "entityId=8ac7a4c790e4d8720190e56cfc7f014f" .
                    "&amount=23.00" .
                    "&paymentType=DB" .
                    "&registrationId=8ac7a4a29154004c019155af79621c25" .
                    "&currency=SAR" .
                    "&testMode=EXTERNAL" .
                    "&standingInstruction.type=RECURRING" .
                    "&standingInstruction.mode=REPEATED" .
                    "&standingInstruction.source=MIT" .
                    "&standingInstruction.recurringType=SUBSCRIPTION" .
                    "&job.second=33" .
                    "&job.minute=43" .
                    "&job.startDate=2024-08-16 00:00:00".
                    "&job.endDate=2024-12-16 00:00:00".
                    "&job.hour=7" .
                    "&job.dayOfMonth=5" .
                    "&job.month=*" .
                    "&job.dayOfWeek=?" .
                    "&job.year=*";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer OGFjN2E0Yzc5MGU0ZDg3MjAxOTBlNTZiYjRiZDAxNDZ8VGczeUNDY0RENmJlRldaOQ=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;


});

Route::get('/cancel', function () {

    $url = "https://eu-test.oppwa.com/scheduling/v1/schedules/8ac7a49f9153f7c9019155b05ea74ba0";
	$url .= "?entityId=8ac7a4c790e4d8720190e56cfc7f014f";
	$url .=	"&testMode=EXTERNAL";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                   'Authorization:Bearer OGFjN2E0Yzc5MGU0ZDg3MjAxOTBlNTZiYjRiZDAxNDZ8VGczeUNDY0RENmJlRldaOQ=='));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if(curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
});


Route::get('/callback', callbackAction::class)->name('callback');


Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/join', [MainController::class, 'join'])->name('join');
Route::post('/join', [MainController::class, 'sendEmail'])->name('sendEmail');
Route::get('/thankyou', [MainController::class, 'thankyouPage'])->name('thankyou');
Route::get('/terms_privacy', [MainController::class, 'terms'])->name('terms_privacy');




Route::get('hash/{password}', function ($password) {

    return Hash::make($password);
});
