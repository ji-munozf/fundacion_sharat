@component('mail::message')
# Hola Soporte
{{ $data['name'] }} le ha enviado un mensaje desde el formulario de contacto de la web de Fundación Sharat.
@component('mail::panel')
{{ $data['mensaje'] }}
@endcomponent

Correo de contacto: {{ $data['email'] }} <br>
Número de contacto: {{ $data['contact_number'] }}

@endcomponent