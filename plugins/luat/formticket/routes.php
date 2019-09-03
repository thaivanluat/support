<?php

	function call($data, $token='') {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://pms2019-dev.giaiphapcrm.vn/api/WebsiteApi.php");
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                                        'Content-Type: application/json;charset=UTF-8',
                                        'Token: '.$token,
                                        'Connection: Keep-Alive'
                                        // 'Cookie: PHPSESSID=' . $token
                                        ));
        curl_setopt($curl, CURLOPT_COOKIE, 'PHPSESSID='.$token);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($curl);
        curl_close($curl);

        if(!$result) {
            return null;    
        }

        $response = json_decode($result);

        return $response;
    }

    function Login()
    {
        $data = array(
            "RequestAction"=> "Login",
            "Credentials" => array(
                "username" => "thaivanluat",
                "password" => "123456"
            )
        );

        $result = call($data);

        $token = $result->token;
        return $token;
    }


    Route::post('/test.php', function()
    {
        if(isset($_POST['email']) && isset($_POST['phone'])) {
            //Get information
            $email = $_POST['email'];
            $phone = $_POST['phone'];

            //Get token;
            $token = Login();

            $vertify_data = array(
                "RequestAction" => "CheckOldContact",
                "Data" => array(
                    "contact_email" => $email,
                    "contact_phone" => $phone
                )
            );

            $isOldContact = call($vertify_data, $token); 

            if($isOldContact->success == 1) {
                $result = "Hop le";            
            }       
            else {
                $result = "Khong Hop le";
            }

            echo json_encode($result);        
        }	 
        else {
            echo "Khong Hop le";
        }  
    });
?>