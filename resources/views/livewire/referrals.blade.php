
<div class="p-6">
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
<div class="flex space-x-4 items-center justify-end px-4 py-3  sm:px-6">
        @if($isEligible)
        <div>
            <x-jet-button wire:click="claimShowModal">
                {{ __('Claim') }}
            </x-jet-button>
        </div>
        @endif

        
    </div>
<div id="wrapper" class="max-w-xl px-4 py-4 mx-auto">
            <div class="sm:grid sm:h-32 sm:grid-flow-row sm:gap-4 sm:grid-cols-3">
            
                <div id="jh-stats-positive" class="flex flex-col justify-center px-4 py-4 bg-white border border-gray-300 rounded">
                    <div>
                        <div>
                            <p class="flex items-center justify-end text-green-500 text-md">
                                <!-- <span class="font-bold">6%</span> -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path class="heroicon-ui" d="M20 15a1 1 0 002 0V7a1 1 0 00-1-1h-8a1 1 0 000 2h5.59L13 13.59l-3.3-3.3a1 1 0 00-1.4 0l-6 6a1 1 0 001.4 1.42L9 12.4l3.3 3.3a1 1 0 001.4 0L20 9.4V15z"/></svg>
                            </p>
                        </div>
                        <p class="text-3xl font-semibold text-center text-gray-800">{{$referralCount}}</p>
                        <p class="text-lg text-center text-gray-500">Total Referrals</p>
                    </div>
                </div>
    
                <div id="jh-stats-negative" class="flex flex-col justify-center px-4 py-4 mt-4 bg-white border border-gray-300 rounded sm:mt-0">
                    <div>
                        <div>
                            <p class="flex items-center justify-end text-red-500 text-md">
                                <!-- <span class="font-bold">6%</span> -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path class="heroicon-ui" d="M20 15a1 1 0 002 0V7a1 1 0 00-1-1h-8a1 1 0 000 2h5.59L13 13.59l-3.3-3.3a1 1 0 00-1.4 0l-6 6a1 1 0 001.4 1.42L9 12.4l3.3 3.3a1 1 0 001.4 0L20 9.4V15z"/></svg>
                                </p>
                        </div>
                        <p class="text-3xl font-semibold text-center text-gray-800">{{$verifiedCount* 500}}</p>
                        <p class="text-lg text-center text-gray-500">Total Amount</p>
                    </div>
                </div>

                <div id="jh-stats-positive" class="flex flex-col justify-center px-4 py-4 bg-white border border-gray-300 rounded">
                    <div>
                        <div>
                            <p class="flex items-center justify-end text-green-500 text-md">
                                <!-- <span class="font-bold">6%</span> -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path class="heroicon-ui" d="M20 15a1 1 0 002 0V7a1 1 0 00-1-1h-8a1 1 0 000 2h5.59L13 13.59l-3.3-3.3a1 1 0 00-1.4 0l-6 6a1 1 0 001.4 1.42L9 12.4l3.3 3.3a1 1 0 001.4 0L20 9.4V15z"/></svg>
                            </p>
                        </div>
                        <p class="text-3xl font-semibold text-center text-gray-800">{{$newReferrals}}</p>
                        <p class="text-lg text-center text-gray-500">New Referrals</p>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->userType == 1)
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="w-full bg-gray-500 h-12">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Number</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Wallet Address</th>
                            @if(Auth::user()->userType == 1)
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr> 
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($referrals as $key=>$referral)
                                    <tr>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{++$key}}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $referral->accountNumber }}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $referral->amount }}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $referral->walletAddress }}</td>
                                        @if(Auth::user()->userType == 1)
                                
                                        <td class="px-6 py-4 text-center text-sm">
                                            <x-jet-button wire:click="approveShowModal({{$referral->id}})">
                                                {{ __('Approve') }}
                                            </x-jet-button>
                                        </td>
                                        @else
                                        <td class="px-6 py-4 text-center text-sm">
                                            No Actions Available
                                        </td>
                                       
                                        @endif
                                    </tr>
                                @endforeach
                                
                        </tbody>
                    </table>
                </div>
                @endif

        <x-jet-dialog-modal wire:model="modalClaimVisible">
        <x-slot name="title">
            {{ __('Claim Referral Bonus') }}
        </x-slot>

        <x-slot name="content">
                <div class="mt-4">
                
                </div>
                <div class="mt-4">
                    <x-jet-label for="walletAddress" value="{{ __('Wallet Address') }}" />
                    <x-jet-input id="walletAddress" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="walletAddress" required />
                </div>
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalClaimVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="claim" wire:loading.attr="disabled">
                {{ __('Claim') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="modalApproveVisible">
        <x-slot name="title">
            {{ __('Approve Referral Claim') }}
        </x-slot>

        <x-slot name="content">
                <div class="mt-4">
                
                </div>
              
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalApproveVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="approve" wire:loading.attr="disabled">
                {{ __('Claim') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>