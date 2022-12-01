<?php

namespace App\Console\Commands;

use App\Models\AsinDetails;
use App\Models\AsinFile;
use App\Models\OutputAsinFile;
use App\Models\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
use SellingPartnerApi\Api\ProductPricingV0Api;
use SellingPartnerApi\Model\ProductPricingV0\CustomerType;
use SellingPartnerApi\Model\ProductPricingV0\GetItemOffersBatchRequest;
use SellingPartnerApi\Model\ProductPricingV0\HttpMethod;
use SellingPartnerApi\Model\ProductPricingV0\ItemCondition;
use SellingPartnerApi\Model\ProductPricingV0\ItemOffersRequest;

class CheckAsinReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:asin_report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Asin Reports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info($this->description." start! - ".date('Y-m-d H:i:ss'));

        $asins_storage_path = storage_path('public/asins');

        $asinFiles = AsinFile::orderBy('id','DESC')->first();

        $asin_storage_path = storage_path('public/output_asin');

        if(!is_dir($asin_storage_path)){
            mkdir($asin_storage_path,0777, true);
        }
        // these are the headers for the csv file. Not required but good to have one incase of system didn't recongize it properly
        $headers = array(
            'Content-Type' => 'text/csv'
        );
        $output_filename = "asin_".$asinFiles->id.date('YmdH').".csv";
        $store_file_name = $asin_storage_path."/".$output_filename;
        $headers = [];
        if(file_exists($store_file_name)){
            @unlink($store_file_name);
//            $handle_read = fopen($store_file_name, 'r');
//            $rowCount = 0;
//            while (($row = fgetcsv($handle_read, 1000000, ",")) !== FALSE) {
//                //Dump out the row for the sake of clarity.
//                if($rowCount == 0){
//                    $headers = $row;
//                }
//                $rowCount++;
//            }

        }
        $handle = fopen($store_file_name, 'w');

//        if(file_exists($store_file_name)){
//
//            $headers = [];
//            //adding the first row
//            if(count($headers) == 0){
//                fputcsv($handle, [
//                    "asin",
//                    "Amazon Prime price",
//                    "Amazon prime price lowest",
//                    "Amazon prime inventory if sold by seller via amazon",
//                    "Lowest buy box price if not prime (includes item cost and shipping cost)",
//                    "if lowest price is prime or not (y/n)",
//                    "seller name",
//                    "seller rating",
//                    "handlign time (hours)",
//                    "shipping price",
//                    "number of sellers (competitor count)"
//                ]);
//            }
//        }else{
            //adding the first row
            if(count($headers) == 0){
                fputcsv($handle, [
                    "asin",
                    "Amazon Prime price",
                    "Amazon prime price lowest",
                    "Amazon prime inventory if sold by seller via amazon",
                    "Lowest buy box price if not prime (includes item cost and shipping cost)",
                    "if lowest price is prime or not (y/n)",
                    "seller name",
                    "seller rating",
                    "handlign time (hours)",
                    "shipping price",
                    "number of sellers (competitor count)"
                ]);
            }
//        }
        if($asinFiles){
            // read csv file and convert into array.
            $filename = $asins_storage_path."/".$asinFiles->filename;
            $document_data_arr = array();
            $burstCount = 0;

            $outputAsin = OutputAsinFile::create([
                'user_id' => $asinFiles->user_id,
                'imported_file_id' => $asinFiles->id,
                'output_file_name' => $output_filename,
                'save_date' => date('Y-m-d')
            ]);
            $asinArr = [];

            LazyCollection::make(function () use ($filename) {
               $file = fopen($filename, 'r');
               while($data = fgetcsv($file)){
                   yield $data;
               }
               fclose($file);
            })->skip(1)->chunk(500)->each(function (LazyCollection $chunk) use ($handle,$asinFiles,$outputAsin,$asinArr,$burstCount){

                $config = Utility::getConfig();
                $apiInstance = new ProductPricingV0Api($config);

                $asins = $chunk->map(function ($row){
                    return $row[0];
                })->toArray();

                $itemBatchRequest = [];
                $throttleLimit = 0;
                foreach ($asins as $key => $asin){
                    if(in_array($asin,$asinArr)){
                        continue;
                    }else{
                        array_push($asinArr,$asin);
                    }
                    $burstCount++;
                    if($burstCount > 20){
                        $burstCount = 1;
                        $config = Utility::getConfig();
                        $apiInstance = new ProductPricingV0Api($config);
//                        sleep(4);
                    }
                    $throttleLimit++;
                    if($throttleLimit == 50){
                        $throttleLimit = 0;
                        sleep(5);
                    }
                    \Log::info($key."- Burst:-".$burstCount." count:- ".count($itemBatchRequest)." - throttleLimit:-".$throttleLimit." ".$asin);
                    if($burstCount == 1 && count($itemBatchRequest) >= 1 && count($itemBatchRequest) <= 20){

                        $get_item_offers_batch_request_body = new GetItemOffersBatchRequest();
                        $get_item_offers_batch_request_body->setRequests($itemBatchRequest);
                        $result = $apiInstance->getItemOffersBatch($get_item_offers_batch_request_body);

                        $this->processResponse($result,$handle,$asinFiles,$outputAsin);

                        $itemBatchRequest = [];
                    }
                    $itemOfferRequest = new ItemOffersRequest();
                    $itemOfferRequest->setCustomerType(CustomerType::CONSUMER);
                    $itemOfferRequest->setMarketplaceId('ATVPDKIKX0DER');
                    $itemOfferRequest->setItemCondition(ItemCondition::_NEW);
                    $itemOfferRequest->setMethod(HttpMethod::GET);
                    $itemOfferRequest->setUri("/products/pricing/v0/items/" . $asin . "/offers");

                    array_push($itemBatchRequest,$itemOfferRequest);
                }
            });

        }
        fclose($handle);
        $this->info($this->description." end - ".date('Y-m-d H:i:ss'));
    }

    public function processResponse($result,$handle,$asinFiles,$outputAsin){
        if($result){
            $responses = $result->getResponses();
            foreach($responses as $response){
                $payload = $response->getBody()->getPayload();

                $prime_price_if_handling_time_3_days = 0.00;
                $prime_price_lowest = 0.00;
                $box_price_lowest = 0.00;
                $is_prime_inventory = null;
                $is_lowest_price_prime = 'No';
                $seller_name = null;
                $seller_rating = 0;
                $sellerCount = [];
                $lowest_handling_time = 0;
                $shipping_price = 0.00;
                $currency = null;

                if($response->getBody()->getErrors()){
                    fputcsv($handle,[
                        $response->getRequest()->getAsin(),
                        $prime_price_if_handling_time_3_days,
                        $prime_price_lowest,
                        $is_prime_inventory,
                        $box_price_lowest,
                        $is_lowest_price_prime,
                        $seller_name,
                        $seller_rating,
                        $lowest_handling_time,
                        $shipping_price,
                        0
                    ]);
                    $asinDetailArr = [
                        'user_id' => $asinFiles->user_id,
                        'import_file_id' => $asinFiles->id,
                        'output_file_id' => $outputAsin->id,
                        'asin' => $response->getRequest()->getAsin(),
                        'prime_price' => $prime_price_if_handling_time_3_days,
                        'prime_price_lowest' => $prime_price_lowest,
                        'prime_inventory' => $is_prime_inventory,
                        'lowest_buy_box_price' => $box_price_lowest,
                        'lowest_price_is_prime' => $is_lowest_price_prime,
                        'seller_name' => $seller_name,
                        'seller_rating' => $seller_rating,
                        'handling_time' => $lowest_handling_time,
                        'shipping_price' => $shipping_price,
                        'number_of_seller' => 0
                    ];
                    AsinDetails::updateOrCreate(['user_id' => $asinFiles->user_id, 'asin' => $response->getRequest()->getAsin()],$asinDetailArr);
                    continue;
                }
                $asin = $payload->getAsin();

                if(isset($payload->getSummary()->getNumberOfOffers()[0])){
                    $fulfillmentChannel = $payload->getSummary()->getNumberOfOffers()[0]->getFulfillmentChannel()->value;
                }

                $lowestPriceArr = [];
                $buyBoxPriceArr = [];
                if($payload->getSummary()->getLowestPrices()){
                    foreach ($payload->getSummary()->getLowestPrices() as $lowestPrice){

                        array_push($lowestPriceArr,$lowestPrice->getListingPrice()->getAmount());
                    }
                }

                if($payload->getSummary()->getBuyBoxPrices()){
                    foreach ($payload->getSummary()->getBuyBoxPrices() as $buyBoxPrice){
                        array_push($buyBoxPriceArr,$buyBoxPrice->getListingPrice()->getAmount());
                    }
                }

                foreach($payload->getOffers() as $offer){
                    $currency = $offer->getListingPrice()->getCurrencyCode();
                    if(!in_array($offer->getSellerId(),$sellerCount)){
                        array_push($sellerCount,$offer->getSellerId());
                    }
                    $handling_time = $offer->getShippingTime()->getMaximumHours();
                    $is_prime = $offer->getPrimeInformation()->getIsPrime();

                    if($is_prime == true && $handling_time <= 72){
                        $prime_price_if_handling_time_3_days = $offer->getListingPrice()->getAmount();
                        $seller_name = $offer->getSellerId();
                    }
                    if($handling_time <= 72){
                        if(count($lowestPriceArr) > 0){
                            $prime_price_lowest = min($lowestPriceArr);
                        }else{
                            $prime_price_lowest = 0.00;
                        }
                    }
                    if($is_prime == false){
                        if(count($buyBoxPriceArr) > 0){
                            $box_price_lowest = min($buyBoxPriceArr);
                        }else{
                            $box_price_lowest = 0.00;
                        }
                    }

                    if($is_prime == true && $prime_price_lowest == $offer->getListingPrice()->getAmount()){
                        $is_lowest_price_prime = "yes";
                    }

                    if($prime_price_lowest == $offer->getListingPrice()->getAmount() && $is_prime != false){
                        if($offer->getSellerFeedbackRating()){
                            $seller_rating = $offer->getSellerFeedbackRating()->getSellerPositiveFeedbackRating();
                        }
                        $lowest_handling_time = $handling_time;
                        $shipping_price = $offer->getShipping()->getAmount();
                    }

                    if($fulfillmentChannel == 'Merchant' && $offer->getIsFeaturedMerchant() == true){
                        $is_prime_inventory = "Yes";
                    }else{
                        $is_prime_inventory = "Amazon";
                    }
                }

                fputcsv($handle,[
                    $asin,
                    ($currency != null) ? Utility::formatCurrency($prime_price_if_handling_time_3_days,$currency) : $prime_price_if_handling_time_3_days,
                    ($currency != null) ? Utility::formatCurrency($prime_price_lowest,$currency) : $prime_price_lowest,
                    $is_prime_inventory,
                    ($currency != null) ? Utility::formatCurrency($box_price_lowest,$currency) : $box_price_lowest,
                    $is_lowest_price_prime,
                    $seller_name,
                    $seller_rating,
                    $lowest_handling_time,
                    ($currency != null) ? Utility::formatCurrency($shipping_price,$currency) : $shipping_price,
                    count($sellerCount)
                ]);
                $asinDetailArr = [
                    'user_id' => $asinFiles->user_id,
                    'import_file_id' => $asinFiles->id,
                    'output_file_id' => $outputAsin->id,
                    'asin' => $asin,
                    'currency' => $currency,
                    'prime_price' => $prime_price_if_handling_time_3_days,
                    'prime_price_lowest' => $prime_price_lowest,
                    'prime_inventory' => $is_prime_inventory,
                    'lowest_buy_box_price' => $box_price_lowest,
                    'lowest_price_is_prime' => $is_lowest_price_prime,
                    'seller_name' => $seller_name,
                    'seller_rating' => $seller_rating,
                    'handling_time' => $lowest_handling_time,
                    'shipping_price' => $shipping_price,
                    'number_of_seller' => count($sellerCount)
                ];
                AsinDetails::updateOrCreate(['user_id' => $asinFiles->user_id, 'asin' => $asin],$asinDetailArr);

            }
        }

    }
}
