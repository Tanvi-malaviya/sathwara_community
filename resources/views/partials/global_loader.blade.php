<div x-data="{ 
         show: false,
         timer: null,
         init() {
             window.addEventListener('pageshow', () => this.hideLoader());
             window.addEventListener('submit', (e) => {
                 const form = e.target;
                 if (form && form.tagName === 'FORM') {
                     if (form.checkValidity && !form.checkValidity()) {
                         return;
                     }
                     setTimeout(() => {
                         if (!e.defaultPrevented) {
                             this.showLoader();
                         }
                     }, 0);
                 }
             });
         },
         showLoader() {
             this.show = true;
             clearTimeout(this.timer);
             // Auto-hide after 15 seconds as a safety fallback to prevent stuck loader
             this.timer = setTimeout(() => {
                 this.show = false;
             }, 15000);
         },
         hideLoader() {
             this.show = false;
             clearTimeout(this.timer);
         }
     }" 
     x-show="show" 
     x-on:show-loader.window="showLoader()" 
     x-on:hide-loader.window="hideLoader()"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/40 backdrop-blur-sm"
     x-cloak>
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-2xl flex flex-col items-center space-y-3.5 max-w-xs text-center">
        <!-- Reusable Spinner Wheel -->
        <div class="relative w-10 h-10">
            <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-primary-500 animate-spin"></div>
        </div>
        <div>
            <h4 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider">Processing...</h4>
            <p class="text-[10px] text-slate-400 font-semibold mt-1">Please wait while the system completes this action.</p>
        </div>
    </div>
</div>
