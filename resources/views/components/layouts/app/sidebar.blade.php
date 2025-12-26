<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <span class="self-center text-2xl font-bold whitespace-nowrap text-blue-600 dark:text-white">
                    DermaNK
                </span>
            </a>

            <flux:navlist variant="outline">
                {{-- 1. DASHBOARD --}}
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>

                {{-- 2. SECCIÓN CLÍNICA (Prioridad Alta) --}}
                <flux:navlist.group heading="Clínica" class="mt-4">
                    @can('ver pacientes')
                        <flux:navlist.item icon="document-text" :href="route('historias.index')" :current="request()->routeIs('historias.index')" wire:navigate>
                            {{ __('Historias Médicas') }}
                        </flux:navlist.item>
                    @endcan

                    @can('ver recipes')
                        <flux:navlist.item icon="clipboard-document-list" :href="route('recipes.index')" :current="request()->routeIs('recipes.index')" wire:navigate>
                            {{ __('Récipes') }}
                        </flux:navlist.item>
                    @endcan

                    @can('ver pacientes') {{-- Usamos el mismo permiso para investigar --}}
                        <flux:navlist.item icon="magnifying-glass" :href="route('buscador')" :current="request()->routeIs('buscador')" wire:navigate>
                            {{ __('Investigación') }}
                        </flux:navlist.item>
                    @endcan
                </flux:navlist.group>

                {{-- 3. SECCIÓN ADMINISTRACIÓN (Solo Admins) --}}
                @if(auth()->user()->can('ver usuarios') || auth()->user()->can('ver roles') || auth()->user()->can('ver permisos'))
                    <flux:navlist.group heading="Administración" class="mt-4">
                        @can('ver usuarios')
                            <flux:navlist.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>
                                {{ __('Usuarios') }}
                            </flux:navlist.item>
                        @endcan

                        @can('ver roles')
                            <flux:navlist.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.index')" wire:navigate>
                                {{ __('Roles') }}
                            </flux:navlist.item>
                        @endcan

                        @can('ver permisos')
                            <flux:navlist.item icon="lock-closed" :href="route('permissions.index')" :current="request()->routeIs('permissions.index')" wire:navigate>
                                {{ __('Permisos') }}
                            </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                @endif

                {{-- 4. HERRAMIENTAS DE SISTEMA --}}
                @can('crear respaldo') {{-- Asumo que usas 'ver usuarios' o un permiso específico --}}
                     <flux:navlist.group heading="Sistema" class="mt-4">
                        <flux:navlist.item icon="arrow-down-tray" :href="route('backup.download')">
                            {{ __('Respaldo BD') }}
                        </flux:navlist.item>
                        {{-- Restaurar (Restore) --}}
                        @can('restaurar respaldo')
        <flux:navlist.item icon="arrow-up-tray" :href="route('backup.restore')" :current="request()->routeIs('backup.restore')" wire:navigate>
            {{ __('Restaurar BD') }}
        </flux:navlist.item>
                        @endcan
                    </flux:navlist.group>

                @endcan


            </flux:navlist>


            <flux:spacer />

            {{-- MENU DE USUARIO (Desktop) --}}
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        {{-- MENU MOVIL (Header) --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
