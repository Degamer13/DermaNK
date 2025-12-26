<div class="flex flex-col items-center justify-center min-h-[80vh] p-6">

    {{-- Logo o Título --}}
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center p-4 mb-4 bg-blue-100 rounded-full dark:bg-blue-900/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-neutral-100">Buscador Médico Rápido</h2>
        <p class="text-gray-500 dark:text-neutral-400">Consulta medicamentos, patologías y dosis sin salir del flujo de trabajo.</p>
    </div>

    {{-- Caja de Búsqueda --}}
    <div x-data="{
            query: @entangle('busqueda'),
            motor: @entangle('motor'),
            realizarBusqueda() {
                if(!this.query) return;

                let url = '';
                // Definimos las URLs de búsqueda
                if(this.motor === 'google') {
                    url = 'https://www.google.com/search?q=' + encodeURIComponent(this.query);
                } else if (this.motor === 'vademecum') {
                    url = 'https://www.vademecum.es/buscar?q=' + encodeURIComponent(this.query);
                } else if (this.motor === 'drugs') {
                    url = 'https://www.drugs.com/search.php?searchterm=' + encodeURIComponent(this.query);
                }

                // Abrimos en nueva pestaña para no cerrar el sistema
                window.open(url, '_blank');
            }
        }"
        class="w-full max-w-2xl"
    >
        <div class="relative group">
            {{-- Efecto de brillo detrás del input --}}
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>

            <div class="relative flex bg-white rounded-lg shadow-xl dark:bg-neutral-900 dark:border dark:border-neutral-700">
                <input
                    x-model="query"
                    @keydown.enter="realizarBusqueda()"
                    type="text"
                    class="w-full p-4 text-lg text-gray-700 bg-transparent border-none rounded-l-lg focus:ring-0 focus:outline-none dark:text-neutral-200 dark:placeholder-neutral-500"
                    placeholder="Escribe el nombre del medicamento o patología..."
                    autofocus
                >
                <button
                    @click="realizarBusqueda()"
                    class="px-8 font-bold text-white transition-colors bg-blue-600 rounded-r-lg hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500"
                >
                    Buscar
                </button>
            </div>
        </div>

        {{-- Selección de Motor --}}
        <div class="flex justify-center gap-4 mt-6">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" value="google" x-model="motor" class="text-blue-600 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-600">
                <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">Google General</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" value="vademecum" x-model="motor" class="text-blue-600 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-600">
                <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">Vademecum (Español)</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" value="drugs" x-model="motor" class="text-blue-600 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-600">
                <span class="text-sm font-medium text-gray-700 dark:text-neutral-300">Drugs.com (Inglés)</span>
            </label>
        </div>
    </div>

    {{-- Accesos Directos --}}
    <div class="grid grid-cols-2 gap-4 mt-12 md:grid-cols-4">
        <a href="https://www.vademecum.es/" target="_blank" class="p-4 text-center transition bg-white border rounded-lg shadow-sm border-gray-100 hover:shadow-md hover:border-blue-300 dark:bg-neutral-900 dark:border-neutral-800 dark:hover:border-blue-700">
            <span class="block font-bold text-blue-600 dark:text-blue-400">Vademecum</span>
            <span class="text-xs text-gray-500 dark:text-neutral-500">Guía Farmacológica</span>
        </a>
        <a href="https://medlineplus.gov/spanish/" target="_blank" class="p-4 text-center transition bg-white border rounded-lg shadow-sm border-gray-100 hover:shadow-md hover:border-blue-300 dark:bg-neutral-900 dark:border-neutral-800 dark:hover:border-blue-700">
            <span class="block font-bold text-green-600 dark:text-green-400">MedlinePlus</span>
            <span class="text-xs text-gray-500 dark:text-neutral-500">Info Pacientes</span>
        </a>
        <a href="https://www.who.int/es" target="_blank" class="p-4 text-center transition bg-white border rounded-lg shadow-sm border-gray-100 hover:shadow-md hover:border-blue-300 dark:bg-neutral-900 dark:border-neutral-800 dark:hover:border-blue-700">
            <span class="block font-bold text-cyan-600 dark:text-cyan-400">OMS</span>
            <span class="text-xs text-gray-500 dark:text-neutral-500">Noticias Salud</span>
        </a>
         <a href="https://google.com" target="_blank" class="p-4 text-center transition bg-white border rounded-lg shadow-sm border-gray-100 hover:shadow-md hover:border-blue-300 dark:bg-neutral-900 dark:border-neutral-800 dark:hover:border-blue-700">
            <span class="block font-bold text-red-500 dark:text-red-400">Google</span>
            <span class="text-xs text-gray-500 dark:text-neutral-500">Búsqueda Libre</span>
        </a>
    </div>

</div>
