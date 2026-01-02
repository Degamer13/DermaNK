<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white tracking-tight">Farmacia</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Gestiona el inventario de medicamentos.</p>
        </div>
        <button wire:click="create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-1 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Medicamento
        </button>
    </div>

    @if (session()->has('message'))
        <div class="flex items-center p-4 mb-6 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-zinc-800 dark:text-green-400 dark:border-green-800 animate-fade-in-down">
            <span class="font-medium mr-2">¡Éxito!</span> {{ session('message') }}
        </div>
    @endif

    <div class="relative mb-6 max-w-lg">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
            <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
        </div>
        <input type="text" wire:model.live="search"
            class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-xl bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 dark:bg-zinc-900 dark:border-zinc-700 dark:placeholder-gray-400 dark:text-white shadow-sm transition-all duration-200"
            placeholder="Buscar por nombre, patología o principio activo...">
    </div>

    <div class="mb-8 overflow-x-auto pb-2">
        <div class="flex flex-wrap gap-2 items-center">
            {{-- Botón TODOS --}}
            <button wire:click="filterByLetter('')"
                class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all border
                {{ $letter == '' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-100 dark:bg-zinc-900 dark:text-gray-400 dark:border-zinc-700 dark:hover:bg-zinc-800' }}">
                TODOS
            </button>

            {{-- Botones A-Z --}}
            @foreach(range('A', 'Z') as $l)
                <button wire:click="filterByLetter('{{ $l }}')"
                    class="w-8 h-8 flex items-center justify-center text-xs font-bold rounded-lg transition-all border
                    {{ $letter == $l ? 'bg-blue-600 text-white border-blue-600 shadow-md transform scale-110' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-100 hover:text-blue-600 dark:bg-zinc-900 dark:text-gray-400 dark:border-zinc-700 dark:hover:bg-zinc-800' }}">
                    {{ $l }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($medicamentos as $med)
        <div class="group bg-white dark:bg-zinc-900 rounded-2xl border border-gray-200 dark:border-zinc-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between overflow-hidden relative">

            <div class="h-2 w-full bg-gradient-to-r from-blue-400 to-indigo-500"></div>

            <div class="p-5 flex-grow">
                <div class="flex justify-between items-start mb-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        {{ $med->patologia }}
                    </span>

                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="edit({{ $med->id }})" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Editar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="confirmDelete({{ $med->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Eliminar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 leading-tight group-hover:text-blue-600 transition-colors">
                    {{ $med->nombre }}
                </h3>

                <div class="space-y-2 mt-4">
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        <span>{{ $med->tipo_medicamento }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>{{ $med->presentacion }}</span>
                    </div>
                </div>

                @if($med->descripcion)
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-zinc-800">
                    <p class="text-xs text-gray-400 italic line-clamp-2">"{{ $med->descripcion }}"</p>
                </div>
                @endif
            </div>

            <div class="md:hidden px-5 pb-5 pt-0">
                <button wire:click="edit({{ $med->id }})" class="w-full py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium border border-gray-200">
                    Editar
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-zinc-800 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                No se encontraron medicamentos
            </h3>

            <p class="mt-1 text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                @if($search && $letter)
                    No hay resultados para la búsqueda <strong>"{{ $search }}"</strong> que empiecen por la letra <strong>"{{ $letter }}"</strong>.
                @elseif($search)
                    No hay resultados para <strong>"{{ $search }}"</strong>.
                @elseif($letter)
                    No hay medicamentos que empiecen por la letra <strong>"{{ $letter }}"</strong>.
                @else
                    Aún no has registrado medicamentos.
                @endif
            </p>

            @if($search || $letter)
                <button wire:click="$set('letter', ''); $set('search', '')" class="mt-4 text-blue-600 hover:text-blue-800 font-medium text-sm hover:underline">
                    Limpiar todos los filtros
                </button>
            @endif
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $medicamentos->links() }}
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full animate-fade-in">
        <div class="relative w-full max-w-lg h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">

                <div class="flex items-center justify-between p-4 border-b dark:border-neutral-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-neutral-100">
                        @if($medicamento_id) Editar Medicamento @else Registrar Nuevo @endif
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[70vh] overflow-y-auto">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Patología</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 group-focus-within:text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                            </div>
                            <input type="text" wire:model="patologia" placeholder="Ej: Acné"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-zinc-800/50 dark:border-zinc-600 dark:text-white dark:placeholder-gray-400 dark:focus:bg-zinc-800 transition-all">
                        </div>
                        @error('patologia') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 group-focus-within:text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <input type="text" wire:model="nombre" placeholder="Ej: Doxiciclina"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-zinc-800/50 dark:border-zinc-600 dark:text-white dark:placeholder-gray-400 dark:focus:bg-zinc-800 transition-all">
                        </div>
                        @error('nombre') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tipo</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 group-focus-within:text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <input type="text" wire:model="tipo_medicamento" placeholder="Ej: Crema"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-zinc-800/50 dark:border-zinc-600 dark:text-white dark:placeholder-gray-400 dark:focus:bg-zinc-800 transition-all">
                        </div>
                        @error('tipo_medicamento') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Presentación</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400 group-focus-within:text-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                            <input type="text" wire:model="presentacion" placeholder="Ej: Tubo 30g"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-zinc-800/50 dark:border-zinc-600 dark:text-white dark:placeholder-gray-400 dark:focus:bg-zinc-800 transition-all">
                        </div>
                        @error('presentacion') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Indicaciones Base</label>
                        <textarea wire:model="descripcion" rows="3" placeholder="Instrucciones generales..."
                            class="block w-full p-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-500 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:bg-zinc-800/50 dark:border-zinc-600 dark:text-white dark:placeholder-gray-400 dark:focus:bg-zinc-800 transition-all resize-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end p-4 border-t gap-3 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900/50 rounded-b-lg">
                    <button wire:click="closeModal" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 dark:bg-zinc-800 dark:text-gray-300 dark:border-zinc-600 dark:hover:bg-zinc-700 transition font-medium">Cancelar</button>
                    <button wire:click="store" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 transition font-medium">{{ $medicamento_id ? 'Actualizar Cambios' : 'Guardar Medicamento' }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full animate-fade-in">
            <div class="relative w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">
                    <button wire:click="$set('confirmingDeleteId', null)" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-neutral-700 dark:hover:text-white"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></button>

                    <div class="p-6 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-neutral-300">¿Estás seguro de eliminar este medicamento?</h3>
                        <p class="mb-5 text-sm text-gray-400 dark:text-neutral-400">Esta acción no se puede deshacer.</p>

                        <button wire:click="delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">Sí, eliminar</button>
                        <button wire:click="$set('confirmingDeleteId', null)" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-neutral-700 dark:text-neutral-300 dark:border-neutral-600 dark:hover:text-white dark:hover:bg-neutral-600">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
