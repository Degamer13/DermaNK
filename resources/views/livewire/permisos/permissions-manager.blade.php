<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-neutral-900 dark:border dark:border-neutral-800">

    {{-- MENSAJES DE ESTADO --}}
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg dark:bg-green-900/30 dark:text-green-400 dark:border dark:border-green-800">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-400 dark:border dark:border-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- ENCABEZADO --}}
    <div class="flex flex-col justify-between gap-4 mb-4 md:flex-row">
        <input wire:model.live="search" type="text"
            class="w-full p-2 border border-gray-300 rounded-lg md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500"
            placeholder="Buscar permiso...">

        @can('crear permisos')
        <button wire:click="create"
            class="px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
            + Nuevo Permiso
        </button>
        @endcan
    </div>

    {{-- TABLA --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-800">
        <table class="min-w-full bg-white dark:bg-neutral-900">
            <thead>
                <tr class="text-sm leading-normal text-gray-600 uppercase bg-gray-100 dark:bg-neutral-800 dark:text-neutral-400">
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Guard</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm font-light text-gray-600 dark:text-neutral-300">
                @forelse($permissions as $perm)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-neutral-800 dark:hover:bg-neutral-800/50 transition">
                        <td class="px-6 py-3 font-bold text-gray-800 dark:text-white">
                            {{ $perm->name }}
                        </td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 text-xs bg-gray-100 rounded text-gray-600 dark:bg-neutral-800 dark:text-gray-400">
                                {{ $perm->guard_name }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                                @can('editar permisos')
                            <button wire:click="edit({{ $perm->id }})" class="mr-2 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300">
                                   <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                                @endcan
                                @can('eliminar permisos')

                            <button wire:click="confirmDelete({{ $perm->id }})" class="text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300">
                             <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </button>
                                @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center dark:text-neutral-500">No hay permisos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $permissions->links() }}
    </div>

    {{-- MODAL CREAR / EDITAR --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm">
        <div class="relative w-full max-w-md bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">

            <div class="flex justify-between items-center p-5 border-b dark:border-neutral-700">
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">
                    {{ $permissionId ? 'Editar Permiso' : 'Crear Permiso' }}
                </h3>
                <button wire:click="closeModal" class="text-gray-400 hover:text-white">X</button>
            </div>

            <div class="p-6">
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-bold text-gray-700 dark:text-neutral-300">Nombre del Permiso</label>
                    <input wire:model="name" type="text" class="w-full p-2.5 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-600 dark:text-white focus:ring-blue-500">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="p-5 border-t bg-gray-50 dark:bg-neutral-900/50 dark:border-neutral-700 flex justify-end gap-2 rounded-b-lg">
                <button wire:click="closeModal" class="px-4 py-2 text-gray-700 bg-gray-300 rounded hover:bg-gray-400 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">Cancelar</button>
                <button wire:click="save" class="px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700">Guardar</button>
            </div>
        </div>
    </div>
    @endif

   {{-- MODAL ELIMINAR (Estilo Unificado) --}}
    @if($confirmingDeleteId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full">
        <div class="relative w-full max-w-md h-full md:h-auto">

            {{-- Contenido del Modal --}}
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">

                {{-- Botón X (Cerrar) --}}
                <button wire:click="$set('confirmingDeleteId', null)" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-neutral-700 dark:hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>

                {{-- Cuerpo --}}
                <div class="p-6 text-center">
                    {{-- Ícono de Alerta --}}
                    <svg class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-neutral-300">¿Eliminar Permiso?</h3>

                    <p class="mb-5 text-sm text-gray-400 dark:text-neutral-400">
                        Si eliminas este permiso, podría afectar el acceso a ciertas funciones del sistema. Esta acción es irreversible.
                    </p>

                    {{-- Botones --}}
                    <div class="flex justify-center gap-2">
                        <button wire:click="delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Sí, eliminar
                        </button>

                        <button wire:click="$set('confirmingDeleteId', null)" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-neutral-700 dark:text-neutral-300 dark:border-neutral-600 dark:hover:text-white dark:hover:bg-neutral-600">
                            No, cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
