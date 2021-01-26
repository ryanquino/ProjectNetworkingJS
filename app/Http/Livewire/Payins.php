<?php

namespace App\Http\Livewire;
use App\Models\Payin;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Payins extends Component
{
    use WithPagination;
    use WithFileUploads;

    //modal variables
    public $modalFormVisible = false;
    public $modalApproveVisible = false;
    public $modalRejectVisible = false;
    
    //model entities
    public $payinId;
    public $userId;
    public $amount;
    public $dateApproved;
    public $status;
    public $created_at;
    public $transactionScreenshot;
    public $amountConfirmed;

    public function createShowModal(){
        $this->clearData();
        $this->modalFormVisible = true;
    }  
    public function clearData(){
        $this->userId = null;
        $this->amount = null;
        $this->amountConfirmed = null;
        $this->dateApproved = null;
        $this->status = null;
        $this->created_at = null;
        $this->transactionScreenshot = null;
    }
    public function create(){
        Payin::create($this->modelData());
        $this->modalFormVisible = false;
        $this->clearData();
        session()->flash('message', 'Payin successfully added.');
    }
    public function approve()
    {
        Payin::find($this->payinId)->update(['status' => 2,'amountConfirmed' => $this->amountConfirmed, 'dateApproved' => date('Y-m-d'), 'approvedBy' => Auth::user()->accountNumber]);
        $userId = DB::table('payins')->where('id', $this->payinId)->value('userId');
        $referralId = DB::table('users')->where('id', $userId)->value('referralId');
        DB::table('referrals')->where('parent', $referralId)->where('child', $userId)->update(['status' => 1]);
        $slots = (int)(($this->amountConfirmed)/12000);
       
        for($i=0;$i<$slots;$i++){
            DB::table('payout_counter')->insert(['payinId' => $this->payinId]);
            DB::table('payouts')->insert(['userId' => $this->userId, 'payinId' => $this->payinId, 'payoutAmount' => ((($this->amountConfirmed-500)/$slots) * 2)]);
        }       
        
        $this->modalApproveVisible = false;
        session()->flash('message', 'Payin successfully approved.');
    }
    public function reject()
    {
        Payin::find($this->payinId)->update(['status' => 1]);
        $this->modalRejectVisible = false;
        session()->flash('message', 'Payin successfully rejected.');
    }
    public function modelData(){
        $this->validate([
            'transactionScreenshot' => 'image|max:1024',
          ]);
    
        // $name = md5($this->transactionScreenshot . microtime()).'.'.$this->transactionScreenshot->extension(); 
        $name = $this->transactionScreenshot->hashName();
        $image = $this->transactionScreenshot->storeAs('public/screenshots', $name);
        
        return [
            'userId' => Auth::id(),
            'amount' => $this->amount,
            'transactionScreenshot' => $name,
        ];
    }  

    public function approveShowModal($id, $userId)
    {
        $data = Payin::find($id);
        
        $this->payinId = $id;
        $this->userId = $userId;
        $this->modalApproveVisible = true;
    }
    public function rejectShowModal($id)
    {
        $this->payinId = $id;
        $this->modalRejectVisible = true;
    }
    public function mount()
    {
        // Resets the pagination after reloading the page
        $this->resetPage();
    }
    public function read(){
        return Payin::paginate(10);
    }
    public function render()
    {
        if(Auth::user()->userType == 0){
            return view('livewire.payin', ['payins'=> Payin::where('userId','=', Auth::id())->orderByDesc('created_at')->paginate(10)]);
        }
        else{
            return view('livewire.payin', ['payins'=> Payin::where('status','=', 0)->orderByDesc('created_at')->paginate(10)]);
        }
        
    }
}
