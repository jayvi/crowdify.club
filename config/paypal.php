<?php
return array(
    // set your paypal credential
    'client_id' => env('PAYPAL_CLIENT_ID','AXCPUkndwkbxzZBudxNyCUqML2MKcGKd9wr6Q7NqZywpkXeTzBdLrIaVp4gp6u0eKHAEALXoGyBrt9Ju'),
    'secret' => env('PAYPAL_SECRET','EBsY4mCCeVRlPN1OH8mpiuV-Czr9CoFTcRD1Vz5V_Re5i7Dvf2tyxZSbWSyEMhtic634JH2URLqIUXfb'),

    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => env('PAYPAL_MODE','live'),

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);