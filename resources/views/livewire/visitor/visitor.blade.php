<div class="container-fluid">
  <div class="row">
    <div class="col d-flex gradient top-table">
      <div>
        <input type="text" wire:model="search" class="form-control" placeholder="Buscar por id, nombre o empresa">
      </div>
      <div class="ms-auto">
        <button wire:click="download" class="btn btn-outline-primary download-btn"><i class="bi bi-download"></i> DESCARGAR</button>
      </div>
    </div>
  </div>
  <div class="row g-3">
    <table class="table">
      <thead class="gradient">
        <tr>
          @if(!\Auth::user()->hasRole('staff'))
          <th scope="col">Acciones</th>
          @endif
          <th scope="col">ID</th>
          <th scope="col">ID público</th>
          <th scope="col">Evento</th>
          <th scope="col">Nombre</th>
          <th scope="col">¿Presente?</th>
          <th scope="col">Email</th>
          <th scope="col">Teléfono</th>
          <th scope="col">Empresa</th>
          <th scope="col">Cargo</th>
          <th scope="col">Provincia</th>
          <th scope="col">Ciudad</th>
        </tr>
      </thead>
      <tbody>
        @foreach($visitors as $visitor)
        <tr>
          @if(!\Auth::user()->hasRole('staff'))
          <td>
            @include('livewire.visitor.actions', ['visitor' => $visitor])
          </td>
          @endif
          <td>{{ $visitor->id }}</td>
          <td>{{ $visitor->custid }}</td>
          <td>{{ $visitor->event->title }}</td>
          <td>{{ $forms[$visitor->form_id]['Nombre completo'] }}</td>
          <td>{{ $visitor->present ? "Si" : "No" }}</td>
          <td>{{ $forms[$visitor->form_id]['Direccion de email'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Telefono'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Empresa'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Cargo'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Provincia'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Localidad'] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>