<?php
namespace App\Helper;
use App\Models\Job;
class JobResponse {

    public function getSingleJobResponse($job_id){
        $data = Job::where("jobs.id", $job_id)
            ->leftJoin('brands', 'jobs.brand_id', '=', 'brands.id')
            ->leftJoin('devices', 'jobs.device_id', '=', 'devices.id')
            ->leftJoin('users', 'jobs.customer_id', '=', 'users.id')
            ->leftJoin('couriers', 'jobs.courier_id', '=', 'couriers.id')
            ->leftJoin('id_cards', 'jobs.id_card_id', '=', 'id_cards.id')
            ->leftJoin('users as shop', 'jobs.user_id', '=', 'shop.id')
            ->leftJoin('statuses as status', 'jobs.status_id', '=', 'status.id')

            ->select(
                'jobs.*',
                'brands.name as brand_name',
                'devices.name as device_model',
                'devices.type as device_name',
                'couriers.name as courier_name',
                'id_cards.name as id_card_name',
                'users.name as customer_name',
                'users.phone as customer_phone_no',
                'shop.id as shop_id',
                'shop.name as shop_name',
                'status.name as status_name',
                'status.color as status_color'
            )
            //                ->with('customer')
            ->with('cards')
            //                ->with('shop')
            ->with('preRepairs')
            ->with('parts')
            ->first();
        if ($data) {
            $device_name = $data['device_name'];
            if ($device_name == 1) {
                $data['device_name'] = "Mobile Phones";

            } else {
                $data['device_name'] = "Laptops";
            }
            $data['device_type_id'] = $device_name;
        }

        return $data;
    }
}
