<div class="p-6 bg-white rounded-lg shadow-lg dark:bg-neutral-900 dark:border dark:border-neutral-800">

    {{-- 1. MENSAJES DE ÉXITO --}}
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

    {{-- 2. ENCABEZADO Y BUSCADOR --}}
    <div class="flex flex-col justify-between gap-4 mb-4 md:flex-row">

        {{-- Input Buscador --}}
        <input wire:model.live="search" type="text"
            class="w-full p-2 border border-gray-300 rounded-lg md:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500"
            placeholder="Buscar por nombre o email...">

        {{-- Botón Crear --}}
        @can('crear usuarios')
        <button wire:click="create"
            class="px-4 py-2 font-bold text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
            + Nuevo Usuario
        </button>
        @endcan
    </div>

    {{-- 3. TABLA DE USUARIOS --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-neutral-800">
        <table class="min-w-full bg-white dark:bg-neutral-900">
            <thead>
                <tr class="text-sm leading-normal text-gray-600 uppercase bg-gray-100 dark:bg-neutral-800 dark:text-neutral-400">
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Rol</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm font-light text-gray-600 dark:text-neutral-300">
                @forelse($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 dark:border-neutral-800 dark:hover:bg-neutral-800/50 transition">

                        {{-- Nombre --}}
                        <td class="px-6 py-3 font-bold text-gray-800 dark:text-neutral-200">
                            {{ $user->name }}
                        </td>

                        {{-- Email --}}
                        <td class="px-6 py-3">
                            {{ $user->email }}
                        </td>

                        {{-- Rol (Badge Adaptado) --}}
                        <td class="px-6 py-3">
                            @if($user->roles->isNotEmpty())
                                @php $roleName = $user->roles->first()->name; @endphp
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    {{ $roleName === 'Administrador' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300' : '' }}
                                    {{ $roleName === 'Medico' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : '' }}
                                    {{ $roleName === 'Asistente' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                                ">
                                    {{ ucfirst($roleName) }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-neutral-500 italic">Sin Rol</span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-6 py-3 text-center">
                            {{-- Botón EDITAR --}}
                            @can('editar usuarios')
                            <button wire:click="edit({{ $user->id }})" class="mr-2 text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            @endcan
                            {{-- Botón ELIMINAR --}}
                            @can('eliminar usuarios')
                            <button wire:click="confirmDelete({{ $user->id }})" class="text-red-500 transition duration-150 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center dark:text-neutral-500">No se encontraron usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>


    {{-- 4. MODAL: CREAR / EDITAR --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full">
        <div class="relative w-full max-w-lg h-full md:h-auto">

            {{-- Contenido del Modal --}}
            <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">

                {{-- Cabecera --}}
                <div class="flex items-center justify-between p-4 border-b dark:border-neutral-700">
                    <h3 class="text-xl font-bold text-gray-800 dark:text-neutral-100">
                        {{ $userId ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-neutral-700 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>

                {{-- Cuerpo del Formulario --}}
                <div class="p-6 space-y-4">

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-1">Nombre Completo <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                        @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-1">
                            Contraseña
                            @if($userId) <span class="text-xs font-normal text-gray-500 dark:text-neutral-500">(Opcional si no desea cambiarla)</span> @endif
                        </label>
                        <input type="password" wire:model="password" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                        @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Rol --}}
                    <div>
                        <label class="block text-gray-700 dark:text-neutral-300 font-medium mb-1">Rol de Acceso <span class="text-red-500">*</span></label>
                        <select wire:model="role" class="w-full p-2 border border-gray-300 rounded-lg dark:bg-neutral-950 dark:border-neutral-700 dark:text-neutral-200 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccione...</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                </div>

                {{-- Pie del Modal (Botones) --}}
                <div class="flex items-center justify-end p-4 border-t gap-2 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900/50 rounded-b-lg">
                    <button wire:click="closeModal" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-100 dark:bg-neutral-700 dark:text-neutral-300 dark:border-neutral-600 dark:hover:bg-neutral-600">
                        Cancelar
                    </button>
                    <button wire:click="save" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 font-bold">
                        Guardar Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- 5. MODAL DE CONFIRMACIÓN (ELIMINAR) --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-neutral-900/80 backdrop-blur-sm md:inset-0 h-modal md:h-full">
            <div class="relative w-full max-w-md h-full md:h-auto">
                <div class="relative bg-white rounded-lg shadow-xl dark:bg-neutral-800 dark:border dark:border-neutral-700">

                    <button wire:click="$set('confirmingDeleteId', null)" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-neutral-700 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>

                    <div class="p-6 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-neutral-300">¿Estás seguro de que deseas eliminar este usuario?</h3>
                        <p class="mb-5 text-sm text-gray-400 dark:text-neutral-400">Esta acción no se puede deshacer y el usuario perderá el acceso.</p>

                        <button wire:click="delete" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Sí, eliminar usuario
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
