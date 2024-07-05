<div>
    @if($institution)
        <p>{{ $institution->name }}</p>
    @else
        <p>No se ha asignado una institución.</p>
    @endif
</div>
