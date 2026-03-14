@props(['name', 'value' => '09:00', 'minHour' => 0, 'maxHour' => 23])

<style>[x-cloak] { display: none !important; }</style>

<div x-data="radialTimePicker('{{ $value }}', {{ $minHour }}, {{ $maxHour }})" 
     x-modelable="internalTime"
     {{ $attributes->only('x-model') }}
     class="relative flex flex-col items-center bg-white p-6 rounded-3xl border border-gray-100 shadow-xl overflow-hidden">

    <!-- Selected Time Display -->
    <div class="flex items-center gap-2 mb-8 select-none">
        <div class="px-5 py-3 bg-indigo-50 rounded-2xl border-2 border-indigo-100 flex items-center justify-center min-w-[120px]">
            <span class="text-4xl font-black text-indigo-700 tracking-tight" x-text="formattedTime"></span>
        </div>
        <div class="text-xs font-bold text-gray-400 uppercase vertical-text tracking-widest px-2">
            Ora Selectată
        </div>
    </div>

    <!-- The Clock Dial -->
    <div class="relative w-72 h-72 flex items-center justify-center bg-gray-50 rounded-full border border-gray-100 shadow-inner group">
        <!-- Center Dot -->
        <div class="absolute w-3 h-3 bg-indigo-500 rounded-full z-20 shadow-sm border-2 border-white"></div>
        
        <!-- Needle (Hour) -->
        <div class="absolute w-1 bg-indigo-400/30 origin-bottom rounded-full transition-all duration-300 ease-out"
             :style="'height: 100px; transform: translateY(-50px) rotate(' + hourRotation + 'deg);'">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-10 h-10 bg-indigo-600 rounded-full shadow-lg border-2 border-white flex items-center justify-center text-white text-xs font-bold ring-4 ring-indigo-100" x-show="activeTab === 'hour'">
                <span x-text="hour"></span>
            </div>
        </div>

        <!-- Needle (Minute) -->
        <div class="absolute w-1 bg-indigo-300/30 origin-bottom rounded-full transition-all duration-300 ease-out"
             :style="'height: 70px; transform: translateY(-35px) rotate(' + minuteRotation + 'deg);'">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-8 bg-indigo-500 rounded-full shadow-md border-2 border-white flex items-center justify-center text-white text-[10px] font-bold ring-4 ring-indigo-50" x-show="activeTab === 'minute'">
                <span x-text="minute"></span>
            </div>
        </div>

        <!-- Outer Ring (Hours Limited by Schedule) -->
        <template x-for="h in 24" :key="'h-'+h">
            <button type="button" 
                    x-show="(h % 24) >= minHour && (h % 24) <= maxHour"
                    @click="setHour(h % 24)"
                    class="absolute w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-200 hover:bg-indigo-100 hover:text-indigo-700 z-10"
                    :class="hour === (h % 24) ? 'text-indigo-700 bg-indigo-50' : 'text-gray-400'"
                    :style="'transform: rotate(' + ((h-1) * 15 - 90) + 'deg) translate(115px) rotate(' + (-(h-1) * 15 + 90) + 'deg);'">
                <span x-text="h % 24 === 0 ? '0' : h % 24"></span>
            </button>
        </template>

        <!-- Inner Ring (Minutes 0-55) -->
        <template x-for="m in 12" :key="'m-'+m">
            <button type="button" 
                    @click="setMinute((m-1) * 5)"
                    class="absolute w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold transition-all duration-200 hover:bg-gray-200 hover:text-gray-800 z-10"
                    :class="minute === ((m-1) * 5) ? 'text-indigo-600 bg-gray-100' : 'text-gray-300'"
                    :style="'transform: rotate(' + ((m-1) * 30 - 90) + 'deg) translate(65px) rotate(' + (-(m-1) * 30 + 90) + 'deg);'">
                <span x-text="String((m-1) * 5).padStart(2, '0')"></span>
            </button>
        </template>
    </div>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name }}" :value="formattedTime">

    <div class="mt-8 flex gap-3">
        <button type="button" @click="activeTab = 'hour'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all"
                :class="activeTab === 'hour' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-100 text-gray-400'">
            SETEAZĂ ORA
        </button>
        <button type="button" @click="activeTab = 'minute'" 
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all"
                :class="activeTab === 'minute' ? 'bg-indigo-600 text-white shadow-lg' : 'bg-gray-100 text-gray-400'">
            SETEAZĂ MINUTELE
        </button>
    </div>
</div>

<script>
function radialTimePicker(initialValue, minHour = 0, maxHour = 23) {
    const parts = (initialValue || '09:00').split(':');
    let startHour = parseInt(parts[0]);
    
    // Clamp initial hour to range
    if (startHour < minHour) startHour = minHour;
    if (startHour > maxHour) startHour = maxHour;

    return {
        hour: startHour,
        minute: parseInt(parts[1]),
        minHour: minHour,
        maxHour: maxHour,
        activeTab: 'hour',
        internalTime: initialValue || '09:00',
        
        init() {
            this.updateInternal(); // Ensure clamped values reflect in internalTime
            this.$watch('hour', () => this.updateInternal());
            this.$watch('minute', () => this.updateInternal());
            this.$watch('internalTime', (val) => {
                if (val && val.includes(':')) {
                    const [h, m] = val.split(':');
                    this.hour = parseInt(h);
                    this.minute = parseInt(m);
                }
            });
        },

        updateInternal() {
            this.internalTime = String(this.hour).padStart(2, '0') + ':' + String(this.minute).padStart(2, '0');
        },

        get formattedTime() {
            return this.internalTime;
        },
        get hourRotation() {
            return this.hour * 15;
        },
        get minuteRotation() {
            return this.minute * 6;
        },
        setHour(h) {
            this.hour = h;
            this.activeTab = 'minute';
        },
        setMinute(m) {
            this.minute = m;
        }
    }
}
</script>
