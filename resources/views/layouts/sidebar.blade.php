<aside id="default-sidebar" 
       class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" 
       aria-label="Sidebar"
       x-data="{ active: '{{ Route::currentRouteName() }}' }"
       :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-900 border-r border-gray-800">
        <div class="flex items-center ps-2.5 mb-8">
            <x-application-logo class="h-8 me-3 fill-current text-indigo-500" />
            <span class="self-center text-xl font-bold whitespace-nowrap text-white">Daser Scheduler</span>
        </div>
        
        <ul class="space-y-2 font-medium">
            @if(auth()->user()->isAdmin())
                <!-- Admin Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Servicii -->
                <li>
                    <a href="{{ route('admin.services.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.services.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.services.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 21 1.9-1.9"/><path d="m3 19 2-2"/><path d="M5 21 3 19"/><path d="M21 3h-4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Z"/><path d="M15 11h6"/><path d="M15 7h6"/><path d="M15 15h6"/><path d="M15 19h6"/><path d="m3 7 11 7"/><path d="m3 11 11-7"/></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Servicii</span>
                    </a>
                </li>

                <!-- Categorii -->
                <li>
                    <a href="{{ route('admin.service-categories.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.service-categories.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.service-categories.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Categorii</span>
                    </a>
                </li>

                <!-- Angajati -->
                <li>
                    <a href="{{ route('admin.employees.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.employees.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Angajați</span>
                    </a>
                </li>

                <!-- Clienti -->
                <li>
                    <a href="{{ route('admin.clients.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.clients.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.clients.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Clienți</span>
                    </a>
                </li>

                <!-- Vouchere -->
                <li>
                    <a href="{{ route('admin.vouchers.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.vouchers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.vouchers.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Vouchere</span>
                    </a>
                </li>

                <!-- Website CMS -->
                <li>
                    <a href="{{ route('admin.website.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.website.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.website.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h16a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H2v-7l2-2.5L2 9V3z"/><path d="M13 3v8"/><path d="M13 17v4"/><path d="M8 3v3"/><path d="M8 11v3"/><path d="M8 18v3"/><path d="M18 3v3"/><path d="M18 11v3"/><path d="M18 18v3"/><path d="m3 9 17-7"/><path d="M3 15h12"/></svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Website</span>
                    </a>
                </li>

                <!-- Setari -->
                <li class="pt-4 mt-4 border-t border-gray-800">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span class="ms-3">Setări</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isEmployee())
                <!-- Employee Dashboard -->
                <li>
                    <a href="{{ route('employee.dashboard') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('employee.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('employee.dashboard') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Employee Schedule -->
                <li>
                    <a href="{{ route('employee.schedule.index') }}" 
                       class="flex items-center p-3 text-gray-300 rounded-xl hover:bg-gray-800 hover:text-white transition-all group {{ request()->routeIs('employee.schedule.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/50' : '' }}">
                        <svg class="w-5 h-5 transition duration-75 group-hover:text-white {{ request()->routeIs('employee.schedule.*') ? 'text-white' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        <span class="ms-3">Programul Meu</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="absolute bottom-4 left-0 w-full px-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-3 text-red-400 rounded-xl hover:bg-red-900/20 transition-all group">
                    <svg class="w-5 h-5 transition duration-75 group-hover:text-red-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    <span class="ms-3">Deconectare</span>
                </button>
            </form>
        </div>
    </div>
</aside>
