<?php namespace Luat\Formticket\Components;

use Cms\Classes\ComponentBase;
use Flash;
use Redirect;

class Form extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'form Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function VertifyCaptcha()
    {
        $post_data = http_build_query(
            array(
                'secret' => "6Lca07MUAAAAABOAV-QfW0WBL38FxMhxbzT2nXSx",
                'response' => $_POST['g-recaptcha-response'],
                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        $context  = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);
        return $result->success;
    }

    public function CallAPI($data, $token='')
    {
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

    public function Login()
    {
        $data = array(
            "RequestAction"=> "Login",
            "Credentials" => array(
                "username" => "thaivanluat",
                "password" => "123456"
            )
        );

        $result = $this->callAPI($data);
        if($result) {
            $token = $result->token; 
        }
        else {
            $token = NULL;                   
        }  
        return $token;
    }

    public function OnSend() 
    {
        $checkCaptcha = $this->VertifyCaptcha();

        if($checkCaptcha == 1) {
            $isOldCustomer = post('OldCustomer');  
            $token = $this->Login();      
            $email = post('Email');
            $phone = post('Phone');

            if($token == NULL) {
                Flash::error('Error');      
                return Redirect::back();  
            }

            if($email == "" && $phone == "") {
                Flash::error('Error');      
                return Redirect::back();  
            }

            if($isOldCustomer == 1) {
                $vertify_data = array(
                    "RequestAction" => "CheckOldContact",
                    "Data" => array(
                        "contact_email" => $email,
                        "contact_phone" => $phone
                    )
                );

                $checkOldContact = $this->CallAPI($vertify_data, $token);     
                if($checkOldContact->success != 1) {
                    Flash::error('Không tồn tại khách hàng với thông tin email và sdt cung cấp! Vui lòng ktra lại!');      
                    return Redirect::back();               
                }       
            }


            $name = post('Name');
            $website = post('Website');
            $serviceType = post('ServiceType');
            $category = post('Type');
            $title = post('Title');
            $description = post('ContentRequired');
   
            // print($token);
            $data = array(
                "RequestAction" => "SaveTicket",
                "Data" => array(
                    "is_old_contact" => $isOldCustomer,
                    "contact_name" => $name,
                    "contact_email" => $email,
                    "contact_phone" => $phone,
                    "domain" => $website.$serviceType,
                    "ticketcategories" => $category,
                    "ticket_title" => $title,
                    "issue" => $description
                )
            );

            $result = $this->CallAPI($data,$token);

            if($result != null) {
                Flash::success('Gửi ticket thành công');            
            }
            else {
                Flash::error('Có lỗi xảy ra trong quá trình gửi');            
            }
            return Redirect::back();        
        }
        else {
            Flash::error('Lỗi xác thực captcha'); 
            return Redirect::back();  
        }
    }
}
