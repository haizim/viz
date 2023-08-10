<x-volt-base>
    <div class="layout--auth is-{!! config('laravolt.ui.login_layout') !!}">
        <div class="layout--auth__container">
            <div class="x-inspire"
                 style="background-image: url('{!! config('laravolt.ui.login_background') !!}')"
            >
                <div class="x-inspire__content" style="width: 100%">
                    <div class="x-inspire__text">
                        @hasSection('left-side')
                            @yield('left-side')
                        @endif
                        
                        @sectionMissing('left-side')
                            <x-volt-inspire/>
                        @endif
                    </div>
                </div>
            </div>


            <div class="x-auth">
                <main class="x-auth__content" up-main="root">
                    <div class="p-2">
                        <x-volt-brand-image/>
                    </div>

                    {{ $slot }}
                    @stack('main')

                </main>
            </div>
        </div>
    </div>
</x-volt-base>
