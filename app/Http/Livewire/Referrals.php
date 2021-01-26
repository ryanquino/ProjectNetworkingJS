<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use QrCode;

class Referrals extends Component
{
    public $modalClaimVisible = false;
    public $modalApproveVisible = false;

    public $walletAddress;
    public $referralId;

    public function claimShowModal(){
        $this->modalClaimVisible = true;
    }
    public function approveShowModal($id){
        $this->referralId = $id;
        $this->modalApproveVisible = true;
    }
    public function approve(){
        DB::table('referral_claims')->where('id', $this->referralId)->update(['status' => 1, 'approvedBy' => Auth::user()->accountNumber]);
        $this->referralId = null;
        $this->modalApproveVisible = false;
        session()->flash('message', 'Approved referral bonus claim request.');
  
    }

    public function claim(){
        $verified = DB::table('referrals')->where('parent', Auth::id())->where('status', 1)->get();
        $verifiedCount = count($verified);
        DB::table('referrals')->where('parent', Auth::id())->where('status', 1)->update(['status' => 2]);
        
        DB::table('referral_claims')->insert(['userId'=>Auth::id(), 'amount'=>$verifiedCount*500, 'walletAddress' => $this->walletAddress]);
        $this->modalClaimVisible = false;
        $this->walletAddress = null;
        session()->flash('message', 'Referral claim is now processing. Please wait 24 hours before checking your wallet.');
    }

    public function render()
    {
        
        $referrals = DB::table('users')->where('referralId', Auth::id())->get();
        $referralCount = count($referrals);
        $verified = DB::table('referrals')->where('parent', Auth::id())->where('status', 1)->get();
        $verifiedCount = count($verified);
        $newReferrals = DB::select('select id from users where WEEK(created_at) = WEEK(CURDATE()) and referralId = ?', [Auth::id()]);
        if(Auth::user()->userType == 1){
            $list = DB::table('referral_claims')->join('users', 'users.id', '=', 'referral_claims.userId')
                        ->select('referral_claims.id', 'users.accountNumber', 'referral_claims.amount', 'referral_claims.walletAddress', 'referral_claims.status')
                        ->where('referral_claims.status', 0)
                        ->get();

                        return view('livewire.referrals', ['referralCount' => $referralCount, 'newReferrals' => count($newReferrals), 'verifiedCount' => $verifiedCount, 'referrals'=> $list]);
        }
        else{
            $idClaim = DB::table('payins')->where('userId', Auth::id())->where('status', 2)->value('id');
            if(isset($idClaim)) $isEligible = true;
            else $isEligible = false;
            return view('livewire.referrals', ['referralCount' => $referralCount, 'newReferrals' => count($newReferrals), 'verifiedCount' => $verifiedCount, 'isEligible' => $isEligible]);
        
        }
    }
}
