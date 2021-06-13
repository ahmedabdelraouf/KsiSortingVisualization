<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * @property  cities
 */
class SortVisulizeController extends Controller
{

    /**
     * @var string
     */
    private $url;

    /**
     * SortVisulizeController constructor.
     */
    public function __construct()
    {
        $this->url = "https://spreadsheets.google.com/feeds/list/0Ai2EnLApq68edEVRNU0xdW9QX1BqQXhHRl9sWDNfQXc/od6/public/basic?alt=json";
    }

    /**
     * @var array[]
     */
    private $cities = [
        [
            "id" => 1,
            "name" => "Damascus",
            "lat" => "33.5074558",
            "lng" => "36.2128553"
        ],
        [
            "id" => 2,
            "name" => "Mogadishu",
            "lat" => "2.0591993",
            "lng" => "45.2366243"
        ],
        [
            "id" => 3,
            "name" => "Ibiza",
            "lat" => "38.9742592",
            "lng" => "1.2773035"
        ],
        [
            "id" => 4,
            "name" => "Cairo Egypt",
            "lat" => "30.0594838",
            "lng" => "31.2234448"
        ],
        [
            "id" => 5,
            "name" => "Tahrir",
            "lat" => "30.0445393",
            "lng" => "31.2330771"
        ],
        [
            "id" => 6,
            "name" => "Nairobi",
            "lat" => "-1.3032051",
            "lng" => "36.7073098"
        ],
        [
            "id" => 7,
            "name" => "Kathmandu",
            "lat" => "27.7089559",
            "lng" => "85.2911133"
        ],
        [
            "id" => 8,
            "name" => "ernabau Madrid Spain",
            "lat" => "40.4353409",
            "lng" => "-3.724183"
        ],
        [
            "id" => 9,
            "name" => "Athens",
            "lat" => "37.990832",
            "lng" => "23.7033199"
        ],
        [
            "id" => 10,
            "name" => "Istanbul",
            "lat" => "41.0049823",
            "lng" => "28.7319945"
        ]
    ];

    /**
     * @return Response
     */
    public function getData(): Response
    {
        return Http::get($this->url);
    }

    /**
     *
     */
    public function sortingVisualizingMessages()
    {
        $data = json_decode($this->getData());
        $content = [];
        $theKey = '$t';
        foreach ($data->feed->entry as $entry) {
            array_push($content, $this->sortString($entry->content->$theKey));
        }
        $groupedData = $this->array_group_by_key($content, 'sentiment');
        return view('sort_map', compact(['groupedData']));
//        return $this->array_group_by_key($content, 'sentiment');
    }

    /**
     * @param $array
     * @param $key
     * @return array
     */
    private function array_group_by_key($array, $key): array
    {
        $return = array();
        foreach ($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    /**
     * @param $string
     * @return array
     */
    public function sortString($string): array
    {
        $finalArray = array();
        $asArr = explode(', ', $string);
        $messageID = explode(': ', $asArr[0]);
        $sentiment = explode(': ', end($asArr));
        $finalArray[$messageID[0]] = $messageID[1];
        $finalArray[$sentiment[0]] = $sentiment[1];
        array_shift($asArr);
        array_pop($asArr);
        $message = explode(': ', implode(' ', $asArr));
        $finalArray[$message[0]] = $message[1];
        $finalArray['city'] = $this->cities[(int)$finalArray['messageid'] - 1];
        return $finalArray;
    }

}
