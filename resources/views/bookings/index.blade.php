<x-booking-layout :settings="$settings">
    <div x-data="bookingFlow()" x-init="init()" class="space-y-8">
        
        <!-- Progress Indicator -->
        <div class="flex items-center justify-between mb-12">
            <template x-for="(step, index) in steps" :key="index">
                <div class="flex items-center flex-1 last:flex-none" x-show="!(index === 0 && !hasCategories)">
                    <div class="relative flex flex-col items-center group">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300"
                             :class="currentStep >= index + 1 ? 'bg-primary border-primary text-white shadow-lg' : 'bg-white border-gray-200 text-gray-400'">
                            <span class="text-xs font-black" x-text="hasCategories ? index + 1 : index"></span>
                        </div>
                        <span class="absolute -bottom-6 text-[10px] font-bold uppercase tracking-widest whitespace-nowrap hidden sm:block"
                              :class="currentStep >= index + 1 ? 'text-gray-900' : 'text-gray-400'"
                              x-text="step.name"></span>
                    </div>
                    <div x-show="index < steps.length - 1" class="flex-grow h-0.5 mx-2 rounded-full transition-all duration-500"
                         :class="currentStep > index + 1 ? 'bg-primary' : 'bg-gray-100'"></div>
                </div>
            </template>
        </div>

        <!-- Step 1: Service Category -->
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
            <h2 class="text-2xl font-black text-gray-900 mb-2">Alege categoria</h2>
            <p class="text-gray-500 mb-8">Selectează tipul de serviciu dorit.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <template x-for="category in categories" :key="category.id">
                    <button @click="selectCategory(category)" 
                            class="relative p-6 bg-white rounded-2xl border-2 transition-all duration-300 text-left group hover:shadow-xl"
                            :class="selectedCategory?.id === category.id ? 'border-primary ring-2 ring-primary/20 bg-primary/5' : 'border-gray-50 hover:border-gray-200'">
                        <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-white transition-colors">
                            <!-- Simple SVG Icons based on category.icon name -->
                            <template x-if="category.icon === 'scissors'">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 13l-2 2m0 0l-2-2m2 2l2 2m2-2l7-7m0 0l2-2m-2 2l2 2m-2-2l7 7m0 0l-2 2m0-2l2 2m-2-2l-7-7"/></svg>
                            </template>
                            <template x-if="category.icon === 'user'">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </template>
                            <template x-if="category.icon === 'box' || !['scissors', 'user'].includes(category.icon)">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </template>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 mb-1" x-text="category.name"></h3>
                        <p class="text-sm text-gray-400 font-medium" x-text="category.description || 'Vezi serviciile'"></p>
                    </button>
                </template>
            </div>
        </div>

        <!-- Step 2: Service List -->
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300">
            <div class="flex items-center mb-8">
                <button @click="hasCategories ? currentStep = 1 : window.location.href='/'" class="p-2 -ml-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 class="text-2xl font-black text-gray-900 ml-2" x-text="selectedCategory ? selectedCategory.name : 'Servicii disponibile'"></h2>
            </div>
            
            <div class="space-y-4">
                <template x-for="service in services" :key="service.id">
                    <button @click="selectService(service)"
                            class="w-full relative p-6 bg-white rounded-2xl border-2 transition-all duration-300 text-left flex items-center justify-between group hover:shadow-lg"
                            :class="selectedService?.id === service.id ? 'border-primary bg-primary/5' : 'border-gray-50 hover:border-gray-200'">
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary transition-colors" x-text="service.name"></h3>
                            <div class="flex items-center text-sm text-gray-400 mt-2 font-semibold">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="service.duration_minutes + ' min'"></span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-gray-900" x-text="service.price + ' LEI'"></span>
                        </div>
                    </button>
                </template>
            </div>
        </div>

        <!-- Step 3: Date & Time -->
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300">
             <div class="flex items-center mb-8">
                <button @click="currentStep = 2" class="p-2 -ml-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 class="text-2xl font-black text-gray-900 ml-2">Data și Ora</h2>
            </div>

            <!-- Horizontal Calendar -->
            <div class="flex overflow-x-auto gap-3 pb-6 hide-scrollbar -mx-4 px-4 sticky top-16 bg-gray-50/90 backdrop-blur-md z-30">
                <template x-for="dateObj in calendarDates" :key="dateObj.value">
                    <button @click="selectDate(dateObj.value)"
                            class="flex-shrink-0 w-16 h-20 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 border-2"
                            :class="selectedDate === dateObj.value ? 'bg-primary border-primary text-white shadow-xl -translate-y-1' : 'bg-white border-white text-gray-400 hover:border-gray-200'">
                        <span class="text-[10px] font-black uppercase tracking-wider mb-1" x-text="dateObj.dayName"></span>
                        <span class="text-xl font-black" x-text="dateObj.dayNumber"></span>
                    </button>
                </template>
            </div>

            <!-- Slots -->
            <div class="mt-8">
                <template x-if="isLoadingSlots">
                    <div class="flex flex-col items-center justify-center py-12 space-y-4">
                        <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-gray-400 font-bold">Se încarcă orele disponibile...</span>
                    </div>
                </template>

                <template x-if="!isLoadingSlots && availableSlots.length === 0">
                    <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-gray-200">
                        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-gray-400 font-bold">Nicio oră disponibilă pentru această zi.</p>
                    </div>
                </template>

                <div class="space-y-10" x-show="!isLoadingSlots && availableSlots.length > 0">
                    <template x-for="empObj in availableSlots" :key="empObj.employee_id">
                        <div>
                            <div class="flex items-center mb-6">
                                <div class="w-1 h-6 bg-primary rounded-full mr-3"></div>
                                <h3 class="text-lg font-black text-gray-900" x-text="empObj.employee_name"></h3>
                            </div>
                            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                <template x-for="slot in empObj.slots" :key="slot">
                                    <button @click="selectSlot(empObj, slot)"
                                            class="p-4 bg-white rounded-2xl border-2 text-sm font-black transition-all duration-300 hover:shadow-md"
                                            :class="selectedSlot === slot && selectedEmployee?.id === empObj.employee_id ? 'border-primary text-primary bg-primary/5 ring-2 ring-primary/10' : 'border-gray-50 text-gray-600 hover:border-gray-200'">
                                        <span x-text="slot"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Step 4: Client Details -->
        <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300">
            <div class="flex items-center mb-8">
                <button @click="currentStep = 3" class="p-2 -ml-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <h2 class="text-2xl font-black text-gray-900 ml-2">Datele tale</h2>
            </div>

            <!-- Booking Summary Card -->
            <div class="bg-gray-900 rounded-3xl p-8 mb-10 text-white shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <span class="text-[10px] font-black tracking-[0.3em] uppercase opacity-50 block mb-4">Sumar Programare</span>
                    <h3 class="text-2xl font-black mb-2" x-text="selectedService?.name"></h3>
                    <div class="flex flex-wrap gap-6 mt-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Data</span>
                                <span class="text-sm font-black" x-text="formatFullDate(selectedDate)"></span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Ora</span>
                                <span class="text-sm font-black" x-text="selectedSlot"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Nume Complet</label>
                    <input type="text" x-model="clientName" placeholder="Ex: Popescu Ion" 
                           class="w-full h-16 px-6 bg-white border-2 border-gray-100 rounded-2xl font-bold focus:border-primary focus:ring-0 transition-all placeholder:text-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Telefon</label>
                    <input type="tel" x-model="clientPhone" placeholder="Ex: 0722 000 000" 
                           class="w-full h-16 px-6 bg-white border-2 border-gray-100 rounded-2xl font-bold focus:border-primary focus:ring-0 transition-all placeholder:text-gray-300">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3 ml-2">Email (Opțional)</label>
                    <input type="email" x-model="clientEmail" placeholder="Ex: ion@gmail.com" 
                           class="w-full h-16 px-6 bg-white border-2 border-gray-100 rounded-2xl font-bold focus:border-primary focus:ring-0 transition-all placeholder:text-gray-300">
                </div>
            </div>
        </div>

        <!-- Step 5: Final Confirmation -->
        <div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300" class="text-center">
            <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">Confirmă Programarea</h2>
            <p class="text-gray-500 mb-12 max-w-sm mx-auto">Ești la un singur pas! Verifică datele și apasă butonul de mai jos.</p>
            
            <div class="bg-white rounded-3xl border-2 border-gray-100 p-8 text-left space-y-6 max-w-sm mx-auto mb-12">
                <div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-1">Serviciu</span>
                    <span class="text-lg font-black text-gray-900" x-text="selectedService?.name"></span>
                </div>
                <div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-1">Când</span>
                    <span class="text-lg font-black text-gray-900" x-text="formatFullDate(selectedDate) + ' la ' + selectedSlot"></span>
                </div>
                <div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] block mb-1">Client</span>
                    <span class="text-lg font-black text-gray-900" x-text="clientName"></span>
                </div>
            </div>

            <template x-if="bookingError">
                 <div class="bg-red-50 text-red-600 p-4 rounded-2xl font-bold mb-6 text-sm" x-text="bookingError"></div>
            </template>
        </div>

        <!-- Sticky Bottom Button -->
        <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-100 p-4 z-50 lg:relative lg:bg-transparent lg:border-0 lg:p-0">
            <div class="max-w-3xl mx-auto flex gap-4">
                <!-- Back Button for Desktop (optional) -->
                
                <button x-show="canContinue"
                        @click="handleContinue"
                        :disabled="isSubmitting"
                        class="flex-grow h-16 bg-primary text-white text-lg font-black rounded-2xl shadow-2xl shadow-primary/30 transition-all active:scale-95 flex items-center justify-center disabled:opacity-50 disabled:active:scale-100">
                    <template x-if="!isSubmitting">
                        <div class="flex items-center">
                            <span x-text="currentStep === 5 ? 'CONFIRMĂ PROGRAMAREA' : 'CONTINUĂ'"></span>
                            <svg x-show="currentStep < 5" class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </template>
                    <template x-if="isSubmitting">
                         <div class="w-8 h-8 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
                    </template>
                </button>
            </div>
        </div>

        <!-- Alpine.js Application Logic -->
        <script>
            function bookingFlow() {
                return {
                    currentStep: 1,
                    steps: [
                        { name: 'Categorie' },
                        { name: 'Serviciu' },
                        { name: 'Data & Ora' },
                        { name: 'Detalii' },
                        { name: 'Confirmare' }
                    ],
                    categories: [],
                    hasCategories: true,
                    services: [],
                    availableSlots: [],
                    calendarDates: [],
                    
                    selectedCategory: null,
                    selectedService: null,
                    selectedDate: null,
                    selectedSlot: null,
                    selectedEmployee: null,
                    
                    clientName: '',
                    clientPhone: '',
                    clientEmail: '',
                    
                    isLoadingSlots: false,
                    isSubmitting: false,
                    bookingError: null,

                    init() {
                        this.fetchCategories();
                        this.generateCalendar();
                        // Default to today
                        this.selectedDate = this.calendarDates[0].value;
                    },

                    async fetchCategories() {
                        try {
                            const res = await fetch('/booking/api/categories');
                            if (!res.ok) throw new Error('Failed to fetch categories');
                            
                            const data = await res.json();
                            this.categories = Array.isArray(data) ? data : [];
                            
                            if (this.categories.length === 0) {
                                this.hasCategories = false;
                                await this.fetchServices(null);
                                this.currentStep = 2;
                            } else {
                                this.hasCategories = true;
                            }
                        } catch (e) {
                            console.error('Category Fetch Error:', e);
                            this.hasCategories = false;
                            this.categories = [];
                            await this.fetchServices(null);
                            this.currentStep = 2;
                        }
                    },

                    async fetchServices(categoryId) {
                        try {
                            const url = categoryId ? `/booking/api/services?category_id=${categoryId}` : '/booking/api/services';
                            const res = await fetch(url);
                            if (!res.ok) throw new Error('Failed to fetch services');
                            this.services = await res.json();
                        } catch (e) {
                            console.error('Service Fetch Error:', e);
                            this.services = [];
                        }
                    },

                    async fetchSlots() {
                        if (!this.selectedService || !this.selectedDate) return;
                        this.isLoadingSlots = true;
                        try {
                            const res = await fetch(`/booking/api/slots?service_id=${this.selectedService.id}&date=${this.selectedDate}`);
                            const data = await res.json();
                            this.availableSlots = data.available_slots;
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.isLoadingSlots = false;
                        }
                    },

                    generateCalendar() {
                        const dates = [];
                        const today = new Date();
                        const locale = 'ro-RO';
                        
                        for (let i = 0; i < 14; i++) {
                            const d = new Date();
                            d.setDate(today.getDate() + i);
                            dates.push({
                                value: d.toISOString().split('T')[0],
                                dayName: d.toLocaleDateString(locale, { weekday: 'short' }).replace('.', ''),
                                dayNumber: d.getDate()
                            });
                        }
                        this.calendarDates = dates;
                    },

                    selectCategory(cat) {
                        this.selectedCategory = cat;
                        this.fetchServices(cat.id);
                        this.currentStep = 2;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    },

                    selectService(service) {
                        this.selectedService = service;
                        this.fetchSlots();
                        this.currentStep = 3;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    },

                    selectDate(date) {
                        this.selectedDate = date;
                        this.selectedSlot = null;
                        this.fetchSlots();
                    },

                    selectSlot(emp, slot) {
                        this.selectedEmployee = { id: emp.employee_id, name: emp.employee_name };
                        this.selectedSlot = slot;
                        // On mobile, maybe don't auto-advance to allow seeing selection
                        // On desktop, we could auto-advance
                    },

                    formatFullDate(dateStr) {
                        if (!dateStr) return '';
                        const d = new Date(dateStr);
                        return d.toLocaleDateString('ro-RO', { weekday: 'long', day: 'numeric', month: 'long' });
                    },

                    get canContinue() {
                        if (this.currentStep === 3) return !!this.selectedSlot;
                        if (this.currentStep === 4) return this.clientName.length > 2 && this.clientPhone.length > 8;
                        if (this.currentStep === 5) return true;
                        return false; 
                    },

                    handleContinue() {
                        if (this.currentStep === 3) {
                            this.currentStep = 4;
                        } else if (this.currentStep === 4) {
                            this.currentStep = 5;
                        } else if (this.currentStep === 5) {
                            this.submitBooking();
                        }
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    },

                    async submitBooking() {
                        this.isSubmitting = true;
                        this.bookingError = null;
                        
                        try {
                            const response = await fetch('/booking/confirm', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    service_id: this.selectedService.id,
                                    employee_id: this.selectedEmployee.id,
                                    date: this.selectedDate,
                                    time: this.selectedSlot,
                                    name: this.clientName,
                                    phone: this.clientPhone,
                                    email: this.clientEmail
                                })
                            });

                            if (response.ok) {
                                // Redirect to success page or handled by controller redirect if it returns 302
                                // Since it's a POST fetch, we might need to handle the redirect manually
                                if (response.redirected) {
                                    window.location.href = response.url;
                                } else {
                                    const data = await response.json();
                                    if (data.redirect) window.location.href = data.redirect;
                                }
                            } else {
                                const errData = await response.json();
                                this.bookingError = errData.message || "A apărut o eroare. Vă rugăm să încercați din nou.";
                            }
                        } catch (e) {
                            this.bookingError = "Eroare de conexiune. Verifică internetul.";
                        } finally {
                            this.isSubmitting = false;
                        }
                    }
                }
            }
        </script>
    </div>
</x-booking-layout>
