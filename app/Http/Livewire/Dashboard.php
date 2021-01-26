<?php

namespace App\Http\Livewire;
use App\Models\Payout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{

    public function payout(){
        $payinId = DB::table('payout_counter')->pluck('id');
        $count = count(DB::table('payout_counter')->get());
        foreach($payinId as $id){
            if($id * 4 + 1 <= $count){
                DB::table('payout_counter')->where('id', $id)->update(['status' => 1]);
                $pId = DB::table('payout_counter')->where('id', $id)->pluck('payinId');
                DB::table('payouts')->where('payinId', $pId)->update(['payoutEligibility' => 1]);
            }
        }
    }
    public function render()
    {
        $payinId = DB::table('payout_counter')->pluck('id');
        $count = count(DB::table('payout_counter')->get());
        foreach($payinId as $id){
            if($id * 4 + 1 <= $count){
                DB::table('payout_counter')->where('id', $id)->update(['status' => 1]);
            }
        }
        return view('livewire.dashboard');
    }
}
