<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-neutral-900 dark:border dark:border-neutral-800">
    {{-- Mensajes de éxito --}}
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-400 dark:border dark:border-green-800">
            {{ session('message') }}
        </div>
    @endif

    {{-- VISTA: LISTA DE HISTORIAS --}}
    @if($view == 'list')
        <div class="flex flex-col justify-between gap-4 mb-4 md:flex-row">
            <input wire:model.live="search" type="text"
                class="w-full p-2 border border-gray-300 rounded-lg md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500"
                placeholder="Buscar por nombre, apellido o cédula..." >

            @can('crear pacientes')
            <button wire:click="create"
                class="px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                + Nueva Historia
            </button>
            @endcan
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-800">
            <table class="min-w-full bg-white dark:bg-neutral-900">
                <thead>
                    <tr class="text-sm leading-normal text-gray-600 uppercase bg-gray-100 dark:bg-neutral-800 dark:text-neutral-400">
                        <th class="px-6 py-3 text-left">N° Historia</th>
                        <th class="px-6 py-3 text-left">Cédula</th>
                        <th class="px-6 py-3 text-left">Paciente</th>
                        <th class="px-6 py-3 text-left">Edad</th>
                        <th class="px-6 py-3 text-left">Teléfono</th>
                        <th class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600 dark:text-neutral-300">
                    @forelse($historias as $historia)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-neutral-800 dark:hover:bg-neutral-800/50">
                            <td class="px-6 py-3 font-bold text-gray-800 dark:text-neutral-200">{{ $historia->codigo_historia }}</td>
                            <td class="px-6 py-3">{{ $historia->cedula }}</td>
                            <td class="px-6 py-3 font-medium">{{ $historia->nombres }} {{ $historia->apellidos }}</td>
                            <td class="px-6 py-3">{{ $historia->fecha_nacimiento->age }} años</td>
                            <td class="px-6 py-3">{{ $historia->telefono }}</td>
                            <td class="px-6 py-3 text-center">
    @can('imprimir historias')
    <a href="{{ route('reporte.historia.individual', $historia->id) }}" target="_blank"
       class="mr-2 text-orange-500 hover:text-orange-600 dark:text-orange-400 dark:hover:text-orange-300"
       title="Imprimir Ficha">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
        </svg>
    </a>
    @endcan
                                @can('ver pacientes')
                                <button wire:click="show({{ $historia->id }})" class="mr-2 text-green-500 hover:text-green-600 dark:text-green-400 dark:hover:text-green-300" title="Ver Detalles">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                @endcan

                                @can('editar pacientes')
                                <button wire:click="edit({{ $historia->id }})" class="mr-2 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                @endcan
                                @can('eliminar pacientes')
                                <button wire:click="confirmDelete({{ $historia->id }})" class="text-red-500 transition duration-150 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center dark:text-neutral-500">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $historias->links() }}
        </div>

        {{-- MODAL DE CONFIRMACIÓN --}}
        @if($confirmingDeleteId)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full">
                <div class="relative w-full max-w-md h-full md:h-auto">
                    <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">
                        <button wire:click="cancelDelete" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-neutral-700 dark:hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div class="p-6 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-neutral-300">¿Estás seguro de que deseas eliminar esta historia?</h3>
                            <p class="mb-5 text-sm text-gray-400 dark:text-neutral-400">Esta acción no se puede deshacer.</p>
                            <button wire:click="delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                Sí, estoy seguro
                            </button>
                            <button wire:click="cancelDelete" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-neutral-700 dark:text-neutral-300 dark:border-neutral-600 dark:hover:text-white dark:hover:bg-neutral-600">
                                No, cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    {{-- VISTA: DETALLE (SHOW) --}}
    @elseif($view == 'show' && $historiaSeleccionada)
        <div class="flex items-center justify-between pb-4 mb-6 border-b dark:border-neutral-700">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">
                Historia Médica <span class="text-blue-600 dark:text-blue-400">{{ $historiaSeleccionada->codigo_historia }}</span>
            </h2>
            <button wire:click="cancel" class="px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                ← Volver al listado
            </button>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            {{-- Tarjeta 1: Datos Personales --}}
            <div class="col-span-1 p-4 bg-blue-50 rounded-lg md:col-span-2 lg:col-span-3 dark:bg-blue-900/10 dark:border dark:border-blue-900/30">
                <h3 class="pb-1 mb-3 text-lg font-bold text-blue-800 border-b border-blue-200 dark:text-blue-300 dark:border-blue-900/50">Datos Personales</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 text-gray-800 dark:text-neutral-200">
                    <div><span class="block text-xs font-bold text-gray-500 uppercase dark:text-neutral-500">Cédula</span> {{ $historiaSeleccionada->cedula }}</div>
                    <div><span class="block text-xs font-bold text-gray-500 uppercase dark:text-neutral-500">Nombre Completo</span> {{ $historiaSeleccionada->nombres }} {{ $historiaSeleccionada->apellidos }}</div>
                    <div><span class="block text-xs font-bold text-gray-500 uppercase dark:text-neutral-500">Edad</span> {{ $historiaSeleccionada->fecha_nacimiento->age }} años ({{ $historiaSeleccionada->fecha_nacimiento->format('d/m/Y') }})</div>
                    <div><span class="block text-xs font-bold text-gray-500 uppercase dark:text-neutral-500">Género</span> {{ $historiaSeleccionada->genero }}</div>
                    <div><span class="block text-xs font-bold text-gray-500 uppercase dark:text-neutral-500">Estado Civil</span> {{ $historiaSeleccionada->estado_civil }}</div>
                    <div><span class="block text-xs font-bold text-gray-500 uppercase dark:text-neutral-500">Lugar Nacimiento</span> {{ $historiaSeleccionada->lugar_nacimiento }}</div>
                </div>
            </div>

            {{-- Tarjeta 2: Contacto --}}
            <div class="p-4 bg-gray-50 border rounded-lg dark:bg-neutral-900 dark:border-neutral-800 dark:text-neutral-300">
                <h3 class="mb-3 font-bold text-gray-700 dark:text-neutral-200">Contacto</h3>
                <p class="mb-2"><span class="font-bold">Teléfono:</span> {{ $historiaSeleccionada->telefono }}</p>
                <p class="mb-2"><span class="font-bold">Teléfono Casa:</span> {{ $historiaSeleccionada->telefono_casa ?? 'N/A' }}</p>
                <p class="mb-2"><span class="font-bold">Email:</span> {{ $historiaSeleccionada->email ?? 'N/A' }}</p>
                <p class="mb-2"><span class="font-bold">Dirección:</span> {{ $historiaSeleccionada->direccion }}</p>
            </div>

            {{-- Tarjeta 3: Socioeconómico --}}
            <div class="p-4 bg-gray-50 border rounded-lg dark:bg-neutral-900 dark:border-neutral-800 dark:text-neutral-300">
                <h3 class="mb-3 font-bold text-gray-700 dark:text-neutral-200">Socioeconómico</h3>
                <p class="mb-2"><span class="font-bold">Ocupación:</span> {{ $historiaSeleccionada->ocupacion }}</p>
                <p class="mb-2"><span class="font-bold">Profesión:</span> {{ $historiaSeleccionada->profesion ?? 'N/A' }}</p>
            </div>

            {{-- Tarjeta 4: Administrativo --}}
            <div class="p-4 bg-gray-50 border rounded-lg dark:bg-neutral-900 dark:border-neutral-800 dark:text-neutral-300">
                <h3 class="mb-3 font-bold text-gray-700 dark:text-neutral-200">Administrativo</h3>
                <p class="mb-2"><span class="font-bold">Seguro:</span> {{ $historiaSeleccionada->seguro }}</p>
                <p class="mb-2"><span class="font-bold">Médico Tratante:</span> {{ $historiaSeleccionada->medico }}</p>
                <p class="mb-2"><span class="font-bold">Referido por:</span> {{ $historiaSeleccionada->referido ?? 'N/A' }}</p>
            </div>

             {{-- NUEVO: Tarjeta 5: Antecedentes Patológicos --}}
             <div class="col-span-1 p-4 bg-red-50 border border-red-100 rounded-lg md:col-span-2 lg:col-span-3 dark:bg-red-900/10 dark:border-red-900/30 dark:text-neutral-300">
                <h3 class="mb-3 font-bold text-red-700 dark:text-red-400">Antecedentes Patológicos</h3>
                @if($historiaSeleccionada->patologias->count() > 0)
                    <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($historiaSeleccionada->patologias as $patologia)
                            <div class="p-3 bg-white rounded shadow-sm dark:bg-neutral-800">
                                <p class="font-bold text-gray-800 dark:text-neutral-200">{{ $patologia->nombre }}</p>
                                @if($patologia->observaciones)
                                    <p class="mt-1 text-sm text-gray-600 dark:text-neutral-400">{{ $patologia->observaciones }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm italic text-gray-500 dark:text-neutral-500">No se registraron antecedentes patológicos.</p>
                @endif
            </div>
        </div>

        <div class="mt-6 text-right">
             <button wire:click="edit({{ $historiaSeleccionada->id }})" class="px-6 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                Editar esta historia
            </button>
        </div>

    {{-- VISTA: FORMULARIO (CREAR / EDITAR) --}}
    @elseif($view == 'form')
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-neutral-100">{{ $historia_id ? 'Editar Historia' : 'Crear Nueva Historia' }}</h2>
        </div>

        {{-- Datos del Paciente --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Cédula <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="cedula" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('cedula') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Nombres <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="nombres" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('nombres') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Apellidos <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="apellidos" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('apellidos') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Fecha Nacimiento <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="date" wire:model="fecha_nacimiento" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('fecha_nacimiento') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Lugar Nacimiento <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="lugar_nacimiento" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('lugar_nacimiento') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Género <span class="text-red-700 dark:text-red-400">*</span></label>
                <select wire:model="genero" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccione...</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
                @error('genero') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Estado Civil <span class="text-red-700 dark:text-red-400">*</span></label>
                <select wire:model="estado_civil" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccione...</option>
                    <option value="Soltero">Soltero(a)</option>
                    <option value="Casado">Casado(a)</option>
                    <option value="Divorciado">Divorciado(a)</option>
                    <option value="Viudo">Viudo(a)</option>
                </select>
                @error('estado_civil') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Dirección <span class="text-red-700 dark:text-red-400">*</span></label>
                <textarea wire:model="direccion" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500"></textarea>
                @error('direccion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 dark:text-neutral-300">Teléfono Personal <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="telefono" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('telefono') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Teléfono Casa (Opcional)</label>
                <input type="text" wire:model="telefono_casa" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Email (Opcional)</label>
                <input type="email" wire:model="email" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Ocupación<span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="ocupacion" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('ocupacion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Profesión (Opcional)</label>
                <input type="text" wire:model="profesion" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Seguro <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="seguro" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('seguro') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Médico Tratante <span class="text-red-700 dark:text-red-400">*</span></label>
                <input type="text" wire:model="medico" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                @error('medico') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
             <div>
                <label class="block text-gray-700 dark:text-neutral-300">Referido por (Opcional)</label>
                <input type="text" wire:model="referido" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        {{-- NUEVO: SECCIÓN DE PATOLOGÍAS DINÁMICA --}}
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-neutral-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-neutral-200 mb-4">Antecedentes Patológicos</h3>

            <div class="space-y-4">
                @foreach($patologias as $index => $patologia)
                    <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-neutral-800 dark:border-neutral-700">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Nombre Patología</label>
                            <input type="text"
                                   wire:model="patologias.{{ $index }}.nombre"
                                   placeholder="Ej: Diabetes, Hipertensión..."
                                   class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-600 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                            @error('patologias.'.$index.'.nombre') <span class="text-xs text-red-500">Campo obligatorio</span> @enderror
                        </div>

                        <div class="w-full md:w-2/3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">Observaciones</label>
                            <input type="text"
                                   wire:model="patologias.{{ $index }}.observaciones"
                                   placeholder="Detalles adicionales, tratamiento, fecha diagnóstico..."
                                   class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-600 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="mt-6">
                            <button wire:click="removePatologia({{ $index }})"
                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-100 rounded-full dark:hover:bg-red-900/30 transition"
                                    title="Eliminar fila">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach

                <button wire:click="addPatologia" class="flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Agregar Patología
                </button>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-6">
            <button wire:click="cancel" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">Cancelar</button>
            <button wire:click="store" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">Guardar Historia</button>
        </div>
    @endif
</div>
