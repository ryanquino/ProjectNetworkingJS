<div class="p-6">
    <div class="flex space-x-4 items-center justify-end px-4 py-3  sm:px-6">
    @if(Auth::user()->userType == 1)
    <x-jet-input id="searchTerm" class="block mt-1 w-full " type="text" wire:model="searchTerm" name="searchTerm" placeholder="Search by user account number" autofocus/>
    @endif
    </div>

    <!-- the data table -->
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <form wire:submit.prevent="update">
    <div>
        @if (session()->has('message'))
        <div role="alert">
            <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                <p>{{ session('message') }}</p>
            </div>
            <br>
        </div>
        @endif
    </div>
    </form>
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    @if(Auth::user()->userType == 1)
                    <table class="w-full bg-gray-500 h-12">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Number</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Payout Amount</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Payout Eligibility</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Wallet Address</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            
                        </tr> 
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payouts as $key=>$payout)
                            <tr>
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{$payout->accountNumber}}</td>
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ round($payout->payoutAmount - ($payout->payoutAmount*0.05),2) }}</td>
                                @if($payout->payoutEligibility == 1)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Eligible
                                    </span>
                                </td>
                                @else
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-green-800">
                                    Not Eligible
                                    </span>
                                </td>
                                @endif
                                @if(!isset($payout->walletAddress))
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-green-800">
                                    Not Eligible
                                    </span>
                                </td>
                                @else
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">{{  QrCode::size(100)->generate($payout->walletAddress) }}</td>
                                @endif
                                @if(!isset($payout->releasedBy))
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                Not yet released
                                </td>
                                @else
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                {{ $payout->releasedBy}}
                                </td>
                                @endif
                                @if($payout->status == 1)
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <x-jet-button wire:click="releaseShowModal({{ $payout->id }})">
                                                {{ __('Release') }}
                                            </x-jet-button>
                                </td>
                                @else
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                No Actions Available
                                </td>
                                @endif
                            </tr>
                            @endforeach
                                
                        </tbody>
                    </table>
                    {{ $payouts->links() }}
                    @else
                    <table class="w-full bg-gray-500 h-12">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Payout Amount</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Package/Slots</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Payout Eligibility</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Wallet Address</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">View Screenshot</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            
                        </tr> 
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payouts as $key=>$payout)
                            <tr>
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{++$key}}</td>
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $payout->payoutAmount }}</td>
                                @if(($payout->payoutAmount/2)/12000 == 1)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Package 1/ 1 Slot</td>
                                @elseif(($payout->payoutAmount/2)/12000 == 3)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Package 2/ 3 Slots</td>
                                @elseif(($payout->payoutAmount/2)/12000 == 5)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Package 3/ 5 Slots</td>
                                @elseif(($payout->payoutAmount/2)/12000 == 10)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Package 4/ 10 Slots</td>
                                @elseif(($payout->payoutAmount/2)/12000 == 30)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Package 5/ 30 Slots</td>
                                @elseif(($payout->payoutAmount/2)/12000 == 50)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Package 6/ 50 Slots</td>
                                @endif
                                @if($payout->payoutEligibility == 1)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Eligible
                                    </span>
                                </td>
                                @else
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-green-800">
                                    Not Eligible
                                    </span>
                                </td>
                                @endif
                                @if(!isset($payout->walletAddress))
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-green-800">
                                    Not Eligible
                                    </span>
                                </td>
                                @else
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">{{  QrCode::size(100)->generate($payout->walletAddress) }}</td>
                                @endif
                                @if(!isset($payout->transactionScreenshot))
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-green-800">
                                    Not Eligible
                                    </span>
                                </td>
                                @else
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                        <a href="{{ asset('storage/screenshots/' . $payout->transactionScreenshot) }}" target="blank">View Image</a></td>
                                @endif        
                                @if($payout->payoutEligibility == 1)
                                @if($payout->status == 1)
                                <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-green-800">
                                    Claimed
                                    </span>
                                </td>
                                @elseif($payout->status == 0)
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <x-jet-button wire:click="claimShowModal({{ $payout->id }})">
                                                {{ __('Claim') }}
                                            </x-jet-button>
                                            <x-jet-danger-button wire:click="reinvestShowModal({{ $payout->id }})">
                                                {{ __('Reinvest') }}
                                            </x-jet-button> 
                                </td>
                                @endif
                                @else
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                No Actions Available
                                </td>
                                @endif
                            </tr>
                            @endforeach
                                
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Form -->
    <x-jet-dialog-modal wire:model="modalClaimVisible">
        <x-slot name="title">
            {{ __('Claim Payout') }}
        </x-slot>

        <x-slot name="content">
                <div class="mt-4">
                
                </div>
                <div class="mt-4">
                    <x-jet-label for="walletAddress" value="{{ __('Enter BTC Wallet Address') }}" />
                    <x-jet-input id="walletAddress" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="walletAddress" required />
                </div>
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalClaimVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="claim" wire:loading.attr="disabled">
                {{ __('Submit') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>    
    
    <!-- Reinvest Modal Form -->
    <x-jet-dialog-modal wire:model="modalReinvestVisible">
        <x-slot name="title">
            {{ __('How Much To Reinvest?') }}
        </x-slot>

        <x-slot name="content">
        <div class="mt-4">
                    <x-jet-label for="amountToReinvest" value="{{ __('Reinvest Amount') }}" />
                    <x-jet-input id="amountToReinvest" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="amountToReinvest" required />
                </div>
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalReinvestVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="reinvest" wire:loading.attr="disabled">
                {{ __('Submit') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    <!-- Release Modal Form -->
    <x-jet-dialog-modal wire:model="modalReleaseVisible">
        <x-slot name="title">
            {{ __('Release Payout') }}
        </x-slot>

        <x-slot name="content">
        <div class="mt-4">
                    <x-jet-label for="transactionScreenshot" value="{{ __('Screenshot of Transaction') }}" />
                    <x-jet-input id="transactionScreenshot" class="block mt-1 w-full" type="file" wire:model.debounce.800ms="transactionScreenshot" required />
                </div>
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalReleaseVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="release" wire:loading.attr="disabled">
                {{ __('Submit') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
