<?php

namespace TempMail;

class TempMail
{
    private ?string $address = null;
    private ?string $token = null;

    public function CreateAddress(?string $name = null, ?int $max = 10, ?int $min = 10) : bool
    {
        if($name != null){
            $Json = [
                "name" => $name
            ];
        }else{
            $Json = [
                "min_name_length" => $min,
                "max_name_length" => $max
            ];
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.internal.temp-mail.io/api/v3/email/new",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($Json),
            CURLOPT_HTTPHEADER => array(
                "Application-Name: web",
                "Application-Version: 2.2.13",
                "Origin: https://temp-mail.io",
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $Json = json_decode($response, true);
        if(isset($Json["token"]) && isset($Json["email"])){
            if($Json["token"] == null || $Json["email"] == null) return false;
            $this->address = $Json["email"];
            $this->token = $Json["token"];
            return true;
        }else{
            return false;
        }
    }

    public function DeleteAddress($token = null, $address = null): bool
    {
        if($token == null) $token = $this->token;
        if($address == null) $address = $this->address;
        if($token != null && $address != null) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.internal.temp-mail.io/api/v3/email/" . $address,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_POSTFIELDS => json_encode([
                    "token" => $token
                ]),
                CURLOPT_HTTPHEADER => array(
                    "Application-Name: web",
                    "Application-Version: 2.2.13",
                    "Origin: https://temp-mail.io",
                    "Content-Type: application/json"
                ),
            ));
            curl_exec($curl);
            curl_close($curl);
        }else{
            return false;
        }
    }

    public function Messages($token = null, $address = null): array
    {
        if($token == null) $token = $this->token;
        if($address == null) $address = $this->address;
        if($token != null && $address != null) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.internal.temp-mail.io/api/v3/email/" . $address . "/messages",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Application-Name: web",
                    "Application-Version: 2.2.13",
                    "Origin: https://temp-mail.io"
                ),
            ));
            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);
            return $response;
        }else{
            return [];
        }
    }

    public function GetAddress(): array
    {
        if($this->address != null) {
            $ex = explode("@", $this->address);
            return [
                "address" => $this->address,
                "token" => $this->token,
                "more" => [
                    "name" => $ex[0],
                    "domain" => $ex[1]
                ],
                "error" => false
            ];
        }else{
            return [
                "error" => true
            ];
        }
    }
}

