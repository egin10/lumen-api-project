<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class FilterDataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function filterData()
    {
        $base_data = array (
            'status' => 1,
            'message' => 'Sukses',
            'data' => 
            array (
              'system_message' => 'SUCCESS',
              'response' => 
              array (
                'additionaldata' => 
                array (
                ),
                'billdetails' => 
                array (
                  0 => 
                  array (
                    'adminfee' => '0.0',
                    'billid' => '8',
                    'currency' => '360',
                    'title' => 'TELKOMSEL 50rb - 50.149',
                    'totalamount' => '50149.00',
                    'descriptions' => NULL,
                    'body' => 
                    array (
                      0 => 'DENOM           : 50000',
                    ),
                  ),
                  1 => 
                  array (
                    'adminfee' => '0.0',
                    'billid' => '9',
                    'currency' => '360',
                    'title' => 'TELKOMSEL 75rb - 74.050',
                    'totalamount' => '74050.00',
                    'descriptions' => NULL,
                    'body' => 
                    array (
                      0 => 'DENOM           : 75000',
                    ),
                  ),
                  2 => 
                  array (
                    'adminfee' => '0.0',
                    'billid' => '10',
                    'currency' => '360',
                    'title' => 'TELKOMSEL 100rb - 98.264',
                    'totalamount' => '98264.00',
                    'descriptions' => NULL,
                    'body' => 
                    array (
                      0 => 'DENOM           : 100000',
                    ),
                  ),
                  3 => 
                  array (
                    'adminfee' => '0.0',
                    'billid' => '11',
                    'currency' => '360',
                    'title' => 'TELKOMSEL 150rb - 146.600',
                    'totalamount' => '146600.00',
                    'descriptions' => NULL,
                    'body' => 
                    array (
                      0 => 'DENOM           : 150000',
                    ),
                  ),
                  4 => 
                  array (
                    'adminfee' => '0.0',
                    'billid' => '12',
                    'currency' => '360',
                    'title' => 'TELKOMSEL 200rb - 194.900',
                    'totalamount' => '194900.00',
                    'descriptions' => NULL,
                    'body' => 
                    array (
                      0 => 'DENOM           : 200000',
                    ),
                  ),
                ),
                'billername' => 'PULSA TSEL',
                'inquiryid' => '27190993',
                'paymenttype' => 'CLOSE_PAYMENT',
                'responsecode' => '0000',
                'responsemsg' => 'SUCCESS',
                'subscriberid' => '081311529594',
                'subscribername' => '',
              ),
              'trace' => 
              array (
                'session_id' => '81215AEFADFB710C1258F79ABA1AD710.node3',
                'request_date_time' => '20190704185319',
                'words' => '779b7f8280415b568cdfd0abcc20eb8c3e18b585',
                'biller_id' => '9900002',
                'account_number' => '081311529594',
                'systrace' => 1564026434,
                'inquiry_id' => '27190993',
              ),
            ),
        );

        $data = [];

        foreach($base_data['data']['response']['billdetails'] as $bill_data) {
            $denom = intval(trim(explode(":", $bill_data['body'][0])[1]));
            if($denom >= 100000) {
                $data[] = $denom;
            }
        }

        print_r($data);
        // return response()->json([
        //     'status'    => 'success',
        //     'message'   => 'List Data with denom >= 100000',
        //     'data'      => $data
        // ], 200);
    }
}
