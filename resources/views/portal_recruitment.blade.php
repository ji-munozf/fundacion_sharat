@section('title', 'Fundación Sharat - Portal Recruitment')

<head>
    <!-- reCAPTCHA Google -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
</head>

<x-app-layout>
    <div class="text-2xl text-center text-gray-900 dark:text-white mt-10 mb-4">
        Portal sharat Recruitment
    </div>

    <div class="container mx-auto p-4">
        <div class="text-gray-900 dark:text-white mx-auto">
            <h1 class="text-lg">¿Qué es el portal Sharat Recruitment?</h1>
            <p class="font-light text-justify text-gray-500 dark:text-gray-400 sm:text-lg">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam congue tortor sodales nisi gravida,
                faucibus elementum erat mattis. Praesent vel magna sed lacus finibus ultrices. Etiam at lacus
                sollicitudin,
                pharetra dui quis, facilisis felis. Donec nec leo nunc. Sed quis lacinia lorem. Suspendisse et leo
                aliquet
                felis cursus blandit sed quis felis. Vestibulum sed augue nec dui fringilla elementum. Sed sed faucibus
                lectus, sed porttitor lacus. Vivamus sagittis varius libero a vehicula. Etiam et fermentum massa.
            </p>
        </div>
    </div>

    <div class="container mx-auto p-4">
        <section class="bg-white dark:bg-gray-900">
            <div class="py-4 px-4 mx-auto">
                <h2 class="mb-4 text-2xl text-center text-gray-900 dark:text-white">Contacte con nosotros
                </h2>
                <p class="mb-4 font-light text-center text-gray-500 dark:text-gray-400 sm:text-lg">¿Estás
                    interesado en subir tus ofertas de trabajo en nuestra plataforma? Contáctanos rellenando este
                    formulario.</p>
                <div class="bg-white shadow rounded-lg p-6 dark:bg-gray-800">
                    <form action="{{ route('portal_recruitment_store') }}" method="POST" class="space-y-4">
                        @csrf

                        <x-validation-errors class="mb-2" />

                        <div>
                            <x-label class="mb-2">
                                Nombre de contacto
                            </x-label>

                            <x-input name="name" type="text" class="w-full"
                                placeholder="Ingrese el nombre de contacto" value="{{ old('name') }}" />
                        </div>

                        <div>
                            <x-label class="mb-2">
                                Email de contacto
                            </x-label>

                            <x-input name='email' type="email" class="w-full"
                                placeholder="Ingrese el email de contacto" value="{{ old('email') }}" />
                        </div>

                        <div>
                            <x-label class="mb-2">Número de contacto</x-label>
                            <x-input id="contact_number" name="contact_number" type="text"
                                class="w-full bg-gray-50 mb-1" placeholder="+569XXXXXXXX"
                                value="{{ old('contact_number') }}"
                                oninput="this.value = this.value.replace(/[^0-9+]/g, '');" />
                            <span class="text-sm text-gray-600 dark:text-gray-400">Recuerde que debe cumplir con el
                                formato indicado.</span>
                        </div>

                        <div>
                            <x-label class="mb-2">
                                Nombre de la institución
                            </x-label>

                            <x-input name="institution" type="text" class="w-full"
                                placeholder="Ingrese el nombre de la institución" value="{{ old('institution') }}" />
                        </div>

                        <div>
                            <x-label class="mb-2">
                                Mensaje
                            </x-label>

                            <textarea id="message" name="message" rows="4"
                                class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-900 dark:border-gray-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ old('mensaje') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fa-solid fa-paper-plane mr-2"></i>
                                Enviar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    @push('js')
        <script>
            document.addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                        action: 'submit'
                    }).then(function(token) {
                        let form = e.target;

                        let input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        input.value = token;

                        form.appendChild(input);
                        form.submit();
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
