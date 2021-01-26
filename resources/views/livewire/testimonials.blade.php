<div class="p-6">
    <div class="flex space-x-4 items-center justify-end px-4 py-3  sm:px-6">
        @if(Auth::user()->userType == 0)
        <div>
            <x-jet-button wire:click="createShowModal">
                {{ __('Add New Testimonial') }}
            </x-jet-button>
        </div>
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
                    <table class="w-full bg-gray-500 h-12">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Number</th>
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Testimonial</th>
                            @if(Auth::user()->userType == 1)
                            <th class="px-6 py-3 bg-gray-50 text-center text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            @endif
                        </tr> 
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($testimonials as $key=>$testimonial)
                                    <tr>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{++$key}}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $testimonial->accountNumber }}</td>
                                        <td class="px-6 py-4 text-center text-sm whitespace-no-wrap">{{ $testimonial->testimonial }}</td>
                                        
                                        @if(Auth::user()->userType == 1)
                                        @if($testimonial->status == 0)
                                        <td class="px-6 py-4 text-center text-sm">
                                            <x-jet-button wire:click="approveShowModal({{ $testimonial->id }})">
                                                {{ __('Approve') }}
                                            </x-jet-button>
                                        </td>
                                        @else
                                        <td class="px-6 py-4 text-center text-sm">
                                            No Actions Available
                                        </td>
                                        @endif
                                        @endif
                                    </tr>
                                @endforeach
                                
                        </tbody>
                    </table>

                    {{ $testimonials->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <x-jet-dialog-modal wire:model="modalApproveVisible">
        <x-slot name="title">
            {{ __('Approve Testimonial') }}
        </x-slot>

        <x-slot name="content">
                
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalApproveVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click.prevent="approve" wire:loading.attr="disabled" onclick="confirm('Are you sure you want to approve this testimonial?) || event.stopImmediatePropagation()">
                {{ __('Approve') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Modal Form -->
    <x-jet-dialog-modal wire:model="modalCreateVisible">
        <x-slot name="title">
            {{ __('Testimonial') }}
        </x-slot>

        <x-slot name="content">
                <div class="mt-4">
                
                </div>
                <div class="mt-4">
                    <x-jet-label for="testimonial" value="{{ __('Testimonial') }}" />
                    <textarea id="testimonial" name="testimonial" rows="5" class="block mt-1 w-full" placeholder="Enter testimonial here..." wire:model.debounce.800ms="testimonial" required></textarea>
                </div>
                
            </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalCreateVisible')" wire:loading.attr="disabled">
                {{ __('Nevermind') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                {{ __('Submit') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
    
</div>
