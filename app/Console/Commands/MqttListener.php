<?php

namespace App\Console\Commands;

use App\Events\NewSensorData;
use App\Models\SensorData;
use Illuminate\Console\Command;
use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

class MqttListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start PubSub Client';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server = 'broker.mqtt-dashboard.com';
        $port = 1883;
        $clientId = "mnzr_" . bin2hex(random_bytes(8));
        $clean_session = true;
        $mqtt_version = MqttClient::MQTT_3_1;

        $connectionSettings = (new ConnectionSettings)
            ->setConnectTimeout(10)
            ->setKeepAliveInterval(60);

        $mqtt = new MqttClient($server, $port, $clientId, $mqtt_version);

        $mqtt->connect($connectionSettings, $clean_session);
        printf("client connected\n");

        $mqtt->subscribe('mnzr/pirdas', function ($topic, $message) {
            // printf("Received message on topic [%s]: %s\n", $topic, $message);

            $data = json_decode($message);

            try {
                SensorData::create([
                    "rain" => $data->r,
                    "soil" => $data->s,
                    "light" => $data->l,
                    "temperature" => $data->t,
                    "humidity" => $data->h,
                    "relay_status" => $data->ss,
                    "time" => date("Y-m-d H:i:s", $data->ts + 7 * 3600), // add 7 hours (gmt +7)
                ]);
            } catch (\Throwable $th) {
                echo $th;
            }

            NewSensorData::dispatch(SensorData::latest('id')->first());
        }, 0);

        $mqtt->loop(true);
    }
}
