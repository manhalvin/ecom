<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Twilio\Rest\Client;

class UserOtp extends Model
{
    use HasFactory;

    protected $table = 'user_otps';

    protected $fillable = ['user_id', 'otp', 'expire_at'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendSMS($receiverNumber)
    {
        $message = "Login OTP is ".$this->otp;

        try {

            $account_sid = "ACe435055b04cfe54cece7b52925ff56a1";
            $auth_token = "3546eb185bdbcd6e986ecbc121fcc9cf";
            $twilio_number = "+16402236030";

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message]);

            info('SMS Sent Successfully.');

        } catch (Exception $e) {
            info("Error: ". $e->getMessage());
        }
    }
}
