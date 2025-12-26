<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg dark:bg-neutral-900 dark:border dark:border-neutral-800">

    {{-- ENCABEZADO: DATOS DEL PACIENTE --}}
    <div class="mb-6 text-center border-b pb-4 dark:border-neutral-800">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Nuevo Récipe Médico</h2>
        <div class="mt-2 text-gray-500 dark:text-neutral-400">
            Recetando a: <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">{{ $paciente->nombre_completo }}</span>
            <span class="mx-2">|</span>
            C.I: {{ $paciente->cedula }}
        </div>
    </div>

    {{-- CUERPO: LISTA DINÁMICA DE MEDICAMENTOS --}}
    <div class="space-y-4">
        @foreach($items as $index => $item)
            <div class="flex flex-col md:flex-row gap-4 items-start p-4 bg-gray-50 rounded-lg border border-gray-200 dark:bg-neutral-950 dark:border-neutral-800 transition-all duration-300">

                {{-- Contador (1, 2, 3...) --}}
                <div class="hidden md:block pt-3">
                    <span class="flex items-center justify-center w-8 h-8 text-sm font-bold text-white bg-blue-600 rounded-full dark:bg-blue-600 shadow-md">
                        {{ $index + 1 }}
                    </span>
                </div>

                {{-- Campos de Texto --}}
                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                    {{-- Columna Medicamento --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                            Medicamento <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            wire:model="items.{{ $index }}.medicamento"
                            class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500"
                            placeholder="Ej: Atamel 500mg">
                        @error('items.'.$index.'.medicamento') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Columna Indicaciones --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1">
                            Indicaciones / Dosis <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            wire:model="items.{{ $index }}.dosis"
                            class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200 dark:placeholder-neutral-500"
                            placeholder="Ej: Tomar 1 tableta cada 8 horas..." rows="1"></textarea>
                        @error('items.'.$index.'.dosis') <span class="text-xs text-red-500 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Botón Eliminar Fila (Solo si hay más de 1) --}}
                <div class="pt-0 md:pt-7 self-end md:self-auto">
                    @if(count($items) > 1)
                        <button wire:click="removeItem({{ $index }})"
                            class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100"
                            title="Quitar este medicamento">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- PIE DE PÁGINA: BOTONES DE ACCIÓN --}}
    <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-6 border-t dark:border-neutral-800 gap-4">

        {{-- Botón Agregar --}}
        <button wire:click="addItem" class="flex items-center gap-2 px-4 py-2 text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50 transition font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Agregar otro medicamento
        </button>

        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('historias.index') }}" class="flex-1 md:flex-none text-center px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700 transition font-medium">
                Cancelar
            </a>

            <button wire:click="save"
                class="flex-1 md:flex-none flex items-center justify-center gap-2 px-8 py-3 text-white bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700 hover:shadow-blue-500/30 dark:bg-blue-600 dark:hover:bg-blue-500 transition font-bold text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                </svg>
                Generar PDF
            </button>
        </div>
    </div>

    {{-- Mensaje de Carga (Opcional, para que el usuario sepa que está procesando) --}}
    <div wire:loading wire:target="save" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg shadow-xl flex items-center gap-4">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-lg font-medium text-gray-700 dark:text-neutral-200">Generando PDF...</span>
        </div>
    </div>
</div>
