<div class="p-6">
    <div class="flex space-x-4 items-center justify-end px-4 py-3  sm:px-6">
        
        <div>
            <x-jet-button wire:click="createShowModal">
                {{ __('Add New Payin') }}
            </x-jet-button>
        </div>
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
                    <table class="w-full bg-gray-500 h-12">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Amount Sent in BTC</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Payin Date</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date Approved</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Screenshot</th>
                            @if(Auth::user()->userType == 1)
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr> 
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($payins as $key=>$payin)
                                    <tr>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{++$key}}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $payin->amount }}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $payin->created_at }}</td>
                                        @if($payin->dateApproved == null)
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Pending Approval</td>
                                        @else
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{$payin->dateApproved}}</td>
                                        @endif
                                        @if(!isset($payin->transactionScreenshot))
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">Reinvestment</td>
                                        @else
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">
                                        <a href="{{ asset('storage/screenshots/' . $payin->transactionScreenshot) }}" target="blank">View Image</a></td>
                                        @endif
                                        @if(Auth::user()->userType == 1)
                                        <td class="px-6 py-4 text-center text-sm">
                                            <x-jet-button wire:click="approveShowModal({{ $payin->id }}, {{$payin->userId}})">
                                                {{ __('Approve') }}
                                            </x-jet-button>
                                            <x-jet-danger-button wire:click="rejectShowModal({{ $payin->id }})">
                                                {{ __('Reject') }}
                                            </x-jet-button> 
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                                
                        </tbody>
                    </table>

                    {{ $payins->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-jet-dialog-modal wire:model="modalApproveVisible">
        <x-slot name="title">
            {{ __('Approve Payin') }}
        </x-slot>

        <x-slot name="content">
                <div class="mt-4">
                
                </div>
                <div class="mt-4">
                    <x-jet-label for="amountConfirmed" value="{{ __('Confirmed Amount') }}" />
                    <x-jet-input id="amountConfirmed" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="amountConfirmed" required />
                </div>
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalApproveVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="approve" wire:loading.attr="disabled">
                {{ __('Approve') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    <x-jet-dialog-modal wire:model="modalRejectVisible">
        <x-slot name="title">
            {{ __('Approve Payin') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to reject this payin? You cannot undo this action once rejected.') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalRejectVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="reject" wire:loading.attr="disabled">
                {{ __('Approve') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    <x-jet-dialog-modal wire:model="modalFormVisible">
            <x-slot name="title">
                {{ __('Save Payin') }}
            </x-slot>

            <x-slot name="content">
            
                <div class="mt-4">
                    <x-jet-label for="amount" value="{{ __('Amount') }}" />
                    <x-jet-input id="amount" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="amount" name="amount" :value="old('amount')" required />
                </div>
                <div class="mt-4">
                    <x-jet-label for="transactionScreenshot" value="{{ __('Screenshot of Transaction') }}" />
                    <x-jet-input id="transactionScreenshot" class="block mt-1 w-full" type="file" wire:model.debounce.800ms="transactionScreenshot" required />
                </div>
                @if ($transactionScreenshot)
                <div class="mt-4">
                    Photo Preview:
                    <img src="{{ $transactionScreenshot->temporaryUrl() }}" style="border: 1px solid #ddd;
                    border-radius: 4px;
                    padding: 5px;
                    width: 200px;">
                </div>
                @endif
                <br>
                <div class="max-w-xl text-sm text-gray-600">
                Please send your payins in BTC in the following QR code or using this address 1GUkzuV1noeSbpFrTfHRscvdnC4AFDDBiR. 
                <br>
                {{QrCode::size(100)->generate('1GUkzuV1noeSbpFrTfHRscvdnC4AFDDBiR') }}

                Disclaimer: Please send only BTC in the address written above. Sending other digital currencies will result to loss of money.
                </div>

            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Submit') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>

    
</div>
