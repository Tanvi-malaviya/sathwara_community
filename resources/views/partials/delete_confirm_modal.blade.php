<!-- Global Delete Confirmation Modal -->
<div x-data="{ 
        open: false, 
        formAction: '', 
        message: '{{ __('messages.delete_confirm_default_message') }}',
        triggerDelete(action, customMsg = '') {
            this.formAction = action;
            if (customMsg) this.message = customMsg;
            this.open = true;
        }
     }" @confirm-delete.window="triggerDelete($event.detail.action, $event.detail.message)" x-show="open"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-transition
    x-cloak>
    <div class="bg-white rounded-3xl p-6 max-w-sm w-full border border-slate-100 shadow-2xl space-y-4"
        @click.away="open = false">
        <div class="flex items-center space-x-3 text-rose-600">
            <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center">
                <svg class="w-6 h-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            </div>
            <h3 class="text-sm font-black text-slate-900">{{ __('messages.delete_confirmation') }}</h3>
        </div>
        <p class="text-xs text-slate-500 font-semibold leading-relaxed" x-text="message"></p>
        <div class="flex justify-end items-center space-x-3 pt-2">
            <button type="button" @click="open = false"
                class="px-4 py-2 border border-slate-200 text-slate-700 font-bold text-xs rounded-xl hover:bg-slate-50 transition-colors">
                {{ __('messages.cancel') }}
            </button>
            <form :action="formAction" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition-colors">
                    {{ __('messages.delete') }}
                </button>
            </form>
        </div>
    </div>
</div>