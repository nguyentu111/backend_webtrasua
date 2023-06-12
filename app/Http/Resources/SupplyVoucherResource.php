<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Warehouse;
use App\Models\Staff;
use App\Models\Branch;
use App\Http\Resources\StaffResource;
// use App\Http\Resources\BrachResource;

class SupplyVoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            //1 đã xác nhận
            //2 chờ vận chuyển
            //3 đang kiểm tra
            //4 hoàn tất
        $status = "";
        if($this->status== 0){
            $status = 'Hủy';
        }
        elseif($this->status== 1){
            $status = 'Đã xác nhận';
        }
        elseif($this->status== 1){
            $status = 'Chờ vận chuyển';
        }
        elseif($this->status== 1){
            $status = 'Đang kiểm tra';
        }
        elseif($this->status== 1){
            $status = 'Hoàn tất';
        }

        $supply_detail = $this->materials()->get();
        $supplyDetail = [];
        $index = 0;
        foreach ($supply_detail as $material) {
            $supplyDetail[$index] = [
                'id' => $material['id'],
                'name' => $material['name'],
                'uom' => $material['uom'],
                'amount' => $material['pivot']['amount']
            ];
            $index +=1;
        }

        return [
            'id' => $this->id,
            'createdAt' => $this->created_at,
            'status' => $status,
            'warehouse' => $this->warehouse_id == null ? 'null' : Warehouse::select('id', 'name', 'address')->where('id', $this->warehouse_id)->get(),
            'staff' => new StaffResource(Staff::find($this->staff_id)),
            'branch' => Branch::select('id', 'name', 'address')->where('id', $this->branch_id)->first(),
            'supplyDetail' => $supplyDetail,
        ];
    }
}
