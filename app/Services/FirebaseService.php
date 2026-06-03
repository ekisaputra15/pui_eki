<?php

namespace App\Services;

class FirebaseService
{
    private $dbUrl;

    public function __construct()
    {
        $this->dbUrl = rtrim(config('services.firebase.database_url'), '/');
    }

    public function set($path, $data)
    {
        if (!$this->dbUrl) return false;

        $url = $this->dbUrl . '/' . ltrim($path, '/') . '.json';
        $json = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function push($path, $data)
    {
        if (!$this->dbUrl) return false;

        $url = $this->dbUrl . '/' . ltrim($path, '/') . '.json';
        $json = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function notifyRole($role, $message, $type = 'info')
    {
        $event = [
            'message' => $message,
            'type' => $type,
            'timestamp' => time() * 1000,
            'role' => $role
        ];
        return $this->push("notifications/$role", $event);
    }

    public function notifyOrderStatus($orderId, $status)
    {
        return $this->set("order_updates/$orderId", [
            'status' => $status,
            'timestamp' => time() * 1000
        ]);
    }
}
