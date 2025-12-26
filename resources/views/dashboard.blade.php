<x-layouts.app :title="__('Dashboard')">
    <div class="flex flex-col flex-1 w-full h-full gap-4 rounded-xl">

        {{-- GRID DE 3 COLUMNAS (CONTADORES) --}}
        <div class="grid gap-4 auto-rows-min md:grid-cols-3">

            {{-- TARJETA 1: TOTAL (Azul) --}}
            <div class="relative overflow-hidden border border-blue-200 aspect-video rounded-xl bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20">
                <div class="flex flex-col items-center justify-center h-full p-6 text-center">
                    <div class="p-3 mb-3 bg-blue-100 rounded-full dark:bg-blue-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-4xl font-extrabold text-blue-900 dark:text-blue-100">{{ $totalGeneral ?? 0 }}</span>
                    <span class="text-sm font-medium text-blue-600 uppercase dark:text-blue-300">Total Pacientes</span>
                </div>
            </div>

            {{-- TARJETA 2: MASCULINO (Cyan/Celeste) --}}
            <div class="relative overflow-hidden border border-cyan-200 aspect-video rounded-xl bg-cyan-50 dark:border-cyan-800 dark:bg-cyan-900/20">
                <div class="flex flex-col items-center justify-center h-full p-6 text-center">
                    <div class="p-3 mb-3 rounded-full bg-cyan-100 dark:bg-cyan-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-cyan-600 dark:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-4xl font-extrabold text-cyan-900 dark:text-cyan-100">{{ $totalMasculino ?? 0 }}</span>
                    <span class="text-sm font-medium text-cyan-600 uppercase dark:text-cyan-300">Hombres</span>
                </div>
            </div>

            {{-- TARJETA 3: FEMENINO (Rosa) --}}
            <div class="relative overflow-hidden border border-pink-200 aspect-video rounded-xl bg-pink-50 dark:border-pink-800 dark:bg-pink-900/20">
                <div class="flex flex-col items-center justify-center h-full p-6 text-center">
                    <div class="p-3 mb-3 rounded-full bg-pink-100 dark:bg-pink-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-pink-600 dark:text-pink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-4xl font-extrabold text-pink-900 dark:text-pink-100">{{ $totalFemenino ?? 0 }}</span>
                    <span class="text-sm font-medium text-pink-600 uppercase dark:text-pink-300">Mujeres</span>
                </div>
            </div>
        </div>

        {{-- CAJA GRANDE INFERIOR (Aquí cargamos la Tabla Livewire) --}}
         {{--<div class="relative flex-1 h-full overflow-hidden border border-neutral-200 rounded-xl dark:border-neutral-700">
            {{-- Opción A: Dejar el patrón original --}}
            {{-- <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" /> --}}

            {{-- Opción B (Recomendada): Cargar tu tabla aquí mismo

        </div>--}}

    </div>
</x-layouts.app>
