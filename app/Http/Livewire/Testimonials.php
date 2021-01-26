<?php

namespace App\Http\Livewire;
use App\Models\Testimonial;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Testimonials extends Component
{
    use WithPagination;

    public $modalApproveVisible = false;
    public $modalCreateVisible = false;
    public $testimonialId;
    public $testimonial;

    public function createShowModal(){
        $this->clearData();
        $this->modalCreateVisible = true;
    }
    public function approveShowModal($id){
        $this->testimonialId = $id;
        $this->modalApproveVisible = true;

    }

    public function create(){
        Testimonial::create(['userId' => Auth::id(), 'testimonial' => $this->testimonial]);
        $this->modalCreateVisible = false;
        $this->clearData();
        session()->flash('message', 'Testimonial successfully added.');
    }
    public function approve(){
        DB::table('testimonials')->where('id', $this->testimonialId)->update(['status' => 1]);
        $this->clearData();
        $this->modalApproveVisible = false;
        session()->flash('message', 'Testimonial successfully approved.');
    }

    public function clearData(){
        $this->testimonialId = null;
        $this->testimonial = null;
    }
    public function render()
    {
        if(Auth::user()->userType == 1){
            return view('livewire.testimonials', ['testimonials' => DB::table('testimonials')->join('users', 'users.id', '=', 'testimonials.userId')->select('testimonials.id', 'users.accountNumber', 'testimonials.testimonial', 'testimonials.status')->orderByDesc('status')->paginate(10)]);    
        }
        else{
            return view('livewire.testimonials', ['testimonials' => DB::table('testimonials')->join('users', 'users.id', '=', 'testimonials.userId')->where('status', 1)->paginate(10)]);    
        }
    }
}
