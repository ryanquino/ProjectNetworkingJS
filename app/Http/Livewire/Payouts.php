<?php

namespace App\Http\Livewire;
use App\Models\Payout;
use App\Models\Payin;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use QrCode;

class Payouts extends Component
{
    use WithPagination;
    use WithFileUploads;

    //modal variables
    public $modalClaimVisible = false;
    public $modalReleaseVisible = false;
    public $modalReinvestVisible = false;

    public $payoutId;
    public $payinId;
    public $payoutAmount;
    public $maturityDate;
    public $walletAddress;
    public $status;
    public $transactionScreenshot;
    public $releasedBy;

    public $amountToReinvest;
    public $searchTerm;

    public function claimShowModal($id){
        $this->payoutId = $id;
        $this->modalClaimVisible = true;

    }
    public function reinvestShowModal($id){
        $this->payoutId = $id;
        $this->modalReinvestVisible = true;
    }
    public function releaseShowModal($id){
        $this->payoutId = $id;
        $this->modalReleaseVisible = true;

    }
    public function claim(){
        Payout::find($this->payoutId)->update(['walletAddress' => $this->walletAddress, 'status' => 1]);
        $this->modalClaimVisible = false;
        session()->flash('message', 'Payout is now processing. Please wait 24 hours before checking your wallet.');
    }
    public function reinvest(){
        if($this->amountToReinvest < 12000 && $this->amountToReinvest <= $this->payoutAmount){
            $this->modalReinvestVisible = false;
            session()->flash('message', 'Amount should be greater than or equal to 12000.');
        }
        else{
            Payin::create(['userId' => Auth::user()->id, 'amount' => $this->amountToReinvest]);
            $this->modalReinvestVisible = false;
            $this->clearData();
            session()->flash('message', 'Reinvestment successfully added.');
        }
    }
    public function release(){
        $this->validate([
            'transactionScreenshot' => 'image|max:1024',
          ]);
        $name = $this->transactionScreenshot->hashName();
        $image = $this->transactionScreenshot->storeAs('public/screenshots', $name);

        Payout::find($this->payoutId)->update(['transactionScreenshot' => $name, 'releasedBy' => Auth::user()->accountNumber, 'status' => 2]);
        $this->modalReleaseVisible = false;
        $this->clearData();
        session()->flash('message', 'Payout successfully released.');
    }
    public function clearData(){
        $this->payoutId = null;
        $this->payinId = null;
        $this->payoutAmount = null;
        $this->maturityDate = null;
        $this->walletAddress = null;
        $this->status = null;
        $this->transactionScreenshot = null;
    }
    public function render()
    {
        if(Auth::user()->userType == 1){
            $query = '%'.$this->searchTerm.'%';

            if($query == NULL){
                $payouts = DB::table('payouts')->join('users', 'users.id', '=', 'payouts.userId')->select('payouts.*', 'users.accountNumber')->where(function($query) {
                    $query->orWhere('status', 0)
                          ->orWhere('status', 1);
                })->orderBy('id', 'desc')->paginate(10);
                return view('livewire.payouts', ['payouts' => $payouts]); 
            }
            else{
                $payouts = DB::table('payouts')->join('users', 'users.id', '=', 'payouts.userId')->select('payouts.*', 'users.accountNumber')->where('users.accountNumber', 'like', $query)->where(function($query) {
                    $query->orWhere('status', 0)
                          ->orWhere('status', 1);
                })->orderBy('id', 'desc')->paginate(10);
                return view('livewire.payouts', ['payouts' => $payouts]); 
            }

        }
        else{
            $payouts = DB::table('payouts')->join('users', 'users.id', '=', 'payouts.userId')->select('payouts.*', 'users.accountNumber')->where('userId', Auth::id())->where(function($query) {
                $query->orWhere('status', 0)
                      ->orWhere('status', 1);
            })->orderBy('id', 'desc')->get();

            return view('livewire.payouts', ['payouts' => $payouts]);
        }
        
        
    }
}
