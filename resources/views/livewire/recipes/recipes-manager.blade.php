<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-neutral-900 dark:border dark:border-neutral-800">

    {{-- Mensajes Flash --}}
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">

    {{-- IZQUIERDA: Buscador --}}
    {{-- Le ponemos un ancho fijo en escritorio (md:w-96) para que no se estire demasiado, pero full en móvil --}}
    <div class="w-full md:w-96">
        <input wire:model.live="search" type="text"
            class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500"
            placeholder="Buscar código o paciente...">
    </div>

    {{-- DERECHA: Botón --}}
    @can('crear recipes')
        <button wire:click="create"
            class="w-full md:w-auto px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700 whitespace-nowrap">
            + Nuevo Récipe
        </button>
    @endcan

</div>

    {{-- Tabla --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-800">
        <table class="min-w-full bg-white dark:bg-neutral-900">
            <thead>
                <tr class="text-xs uppercase bg-gray-100 text-gray-600 dark:bg-neutral-800 dark:text-neutral-400">
                    <th class="px-6 py-3 text-left">Código</th>
                    <th class="px-6 py-3 text-left">Fecha</th>
                    <th class="px-6 py-3 text-left">Paciente</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm dark:text-neutral-300 divide-y divide-gray-200 dark:divide-neutral-800">
                @forelse($recipes as $recipe)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800/50">
                        <td class="px-6 py-3 font-bold text-blue-600 dark:text-blue-400">{{ $recipe->codigo }}</td>
                        <td class="px-6 py-3">{{ $recipe->fecha->format('d/m/Y') }}</td>
                        <td class="px-6 py-3">
                            <div class="font-bold">{{ $recipe->paciente->nombre_completo }}</div>
                            <div class="text-xs text-gray-500 dark:text-neutral-500">{{ $recipe->paciente->cedula }}</div>
                        </td>
                        <td class="px-6 py-3 text-center flex justify-center gap-3">
                            @can('descargar recipes')

                            {{-- PDF --}}
                            <a href="{{ route('recipe.pdf', $recipe->id) }}" target="_blank" class="text-red-500 hover:text-red-700" title="Ver PDF">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>
                            </a>
                            @endcan

                            @can('editar recipes')
                            {{-- Editar --}}
                            <button wire:click="edit({{ $recipe->id }})"  class="mr-2 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300" title="Editar">
                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                            </button>
                            @endcan
                            @can('eliminar recipes')
                            {{-- Eliminar --}}
                            <button wire:click="confirmDelete({{ $recipe->id }})" class="text-red-500 transition duration-150 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                            </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center dark:text-neutral-500">No hay récipes.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-2">{{ $recipes->links() }}</div>
    </div>

    {{-- MODAL --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-2xl dark:bg-neutral-900 dark:border dark:border-neutral-800 max-h-[90vh] overflow-y-auto">

            {{-- Header Modal --}}
            <div class="flex justify-between items-center p-5 border-b dark:border-neutral-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $recipeId ? 'Editar Récipe' : 'Nuevo Récipe' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">

                {{-- 1. SELECCIONAR PACIENTE --}}
                <div class="bg-gray-50 p-4 rounded-lg border dark:bg-neutral-950 dark:border-neutral-800">
                    <label class="block font-bold mb-2 text-sm uppercase text-gray-500 dark:text-neutral-400">Paciente</label>

                    @if($selectedPatient)
                        <div class="flex justify-between items-center bg-blue-50 border border-blue-200 p-3 rounded dark:bg-blue-900/20 dark:border-blue-800">
                            <div>
                                <div class="font-bold text-blue-900 dark:text-blue-300">{{ $selectedPatient->nombre_completo }}</div>
                                <div class="text-xs text-blue-700 dark:text-blue-400">{{ $selectedPatient->cedula }}</div>
                            </div>
                            <button wire:click="removePatient" class="text-xs text-red-500 hover:underline">Cambiar</button>
                        </div>
                    @else
                        <div class="relative">
                            <input wire:model.live="searchPatient" type="text" placeholder="Escribe nombre o cédula..."
                                class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">

                            @if(!empty($patientsFound))
                                <ul class="absolute z-10 w-full bg-white border rounded shadow-xl mt-1 max-h-40 overflow-y-auto dark:bg-neutral-800 dark:border-neutral-700">
                                    @foreach($patientsFound as $p)
                                        <li wire:click="selectPatient({{ $p->id }})" class="p-2 hover:bg-blue-100 cursor-pointer dark:text-neutral-200 dark:hover:bg-neutral-700">
                                            <span class="font-bold">{{ $p->nombre_completo }}</span> ({{ $p->cedula }})
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @error('selectedPatient') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    @endif
                </div>

                {{-- 2. FECHA Y NOTAS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold mb-1 text-sm text-gray-700 dark:text-neutral-300">Fecha</label>
                        <input wire:model="fecha" type="date" class="w-full p-2 border rounded dark:bg-neutral-950 dark:border-neutral-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block font-bold mb-1 text-sm text-gray-700 dark:text-neutral-300">Observaciones</label>
                        <input wire:model="observaciones" type="text" class="w-full p-2 border rounded dark:bg-neutral-950 dark:border-neutral-700 dark:text-white">
                    </div>
                </div>

                {{-- 3. MEDICAMENTOS DINÁMICOS --}}
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="font-bold text-gray-700 dark:text-neutral-300">Medicamentos</label>
                        <button wire:click="addItem" class="text-sm text-blue-600 font-bold hover:underline">+ Agregar Otro</button>
                    </div>

                    <div class="space-y-3">
                        @foreach($items as $index => $item)
                            <div class="flex gap-2 items-start">
                                <div class="w-1/3">
                                    <input wire:model="items.{{ $index }}.medicamento" type="text" placeholder="Medicamento" class="w-full p-2 border rounded dark:bg-neutral-950 dark:border-neutral-700 dark:text-white text-sm">
                                    @error('items.'.$index.'.medicamento') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                                </div>
                                <div class="w-2/3">
                                    <input wire:model="items.{{ $index }}.indicaciones" type="text" placeholder="Indicaciones / Dosis" class="w-full p-2 border rounded dark:bg-neutral-950 dark:border-neutral-700 dark:text-white text-sm">
                                    @error('items.'.$index.'.indicaciones') <span class="text-red-500 text-[10px] block">{{ $message }}</span> @enderror
                                </div>
                                @if(count($items) > 1)
                                    <button wire:click="removeItem({{ $index }})" class="text-red-500 hover:text-red-700 pt-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex justify-end gap-2 p-5 border-t dark:border-neutral-800 bg-gray-50 dark:bg-neutral-950 rounded-b-xl">
                <button wire:click="closeModal" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">Cancelar</button>
                <button wire:click="save" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 font-bold">Guardar</button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL DE CONFIRMACIÓN --}}
        @if($confirmingDeleteId)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full">
                <div class="relative w-full max-w-md h-full md:h-auto">
                    {{-- Modal Content: Usamos neutral-800 para diferenciarlo del fondo 900 --}}
                    <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">
                        <button wire:click="cancelDelete" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-neutral-700 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div class="p-6 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-neutral-300">¿Estás seguro de que deseas eliminar este recipe?</h3>
                            <p class="mb-5 text-sm text-gray-400 dark:text-neutral-400">Esta acción no se puede deshacer.</p>
                            <button wire:click="delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Sí, estoy seguro
                            </button>
                            <button wire:click="$set('confirmingDeleteId', null)" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-neutral-700 dark:text-neutral-300 dark:border-neutral-600 dark:hover:text-white dark:hover:bg-neutral-600">
                                No, cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>
