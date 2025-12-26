<div class="max-w-2xl mx-auto mt-10 p-6 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-red-200 dark:border-red-900">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900 mb-4">
            <flux:icon.exclamation-triangle class="w-8 h-8 text-red-600 dark:text-red-400" />
        </div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Restaurar Base de Datos</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Sube tu archivo de respaldo (ZIP o SQL). <br>
            <span class="font-bold text-red-500">ADVERTENCIA:</span> Esto borrará todos los datos actuales y los reemplazará con los del respaldo.
        </p>
    </div>

    <form wire:submit.prevent="restore" class="space-y-6">

        <div class="flex items-center justify-center w-full">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    @if($backupFile)
                        <flux:icon.document-check class="w-10 h-10 text-green-500 mb-3" />
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">{{ $backupFile->getClientOriginalName() }}</p>
                    @else
                        <flux:icon.cloud-arrow-up class="w-10 h-10 text-gray-400 mb-3" />
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Haz clic para subir</span> o arrastra el archivo</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">ZIP o SQL (MAX. 50MB)</p>
                    @endif
                </div>
                <input id="dropzone-file" type="file" wire:model="backupFile" class="hidden" />
            </label>
        </div>

        @error('backupFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        @if($status)
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
                <span class="font-medium">Estado:</span> {{ $status }}
            </div>
        @endif

        <div class="flex justify-center">
            <button type="submit"
                wire:loading.attr="disabled"
                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 w-full disabled:opacity-50 disabled:cursor-not-allowed">

                <span wire:loading.remove>Restaurar Sistema Ahora</span>
                <span wire:loading>
                    <svg aria-hidden="true" role="status" class="inline w-4 h-4 me-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Procesando...
                </span>
            </button>
        </div>
    </form>
</div>
