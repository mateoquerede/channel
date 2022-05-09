<div class="container-fluid" wire:init="loadVisitors">
  <div class="row">
    <div class="col d-flex gradient top-table">
      <div class="d-flex">
        <select wire:model="cant" class="mx-2 form-select">
          <option value=10>10</option>
          <option value=25>25</option>
          <option value=50>50</option>
          <option value=100>100</option>
        </select>
      </div>
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
          <th scope="col">Nombre</th>
          <th scope="col">Empresa</th>
          <th scope="col">Cargo</th>
        </tr>
      </thead>
      <tbody>
        @if(count($visitors))
        @foreach($visitors as $visitor)
        <tr>
          @if(!\Auth::user()->hasRole('staff'))
          <td>
            @if($visitor->event->id == substr($this->currentEvent, strrpos($this->currentEvent, ' ') + 1))
            @include('livewire.exhvisitor.actions', ['visitor' => $visitor])
            @endif
          </td>
          @endif
          <td>{{ $visitor->id }}</td>
          <td>{{ $visitor->custid }}</td>
          <td>{{ $forms[$visitor->form_id]['Nombre completo'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Empresa'] }}</td>
          <td>{{ $forms[$visitor->form_id]['Cargo'] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @if($visitors->hasPages())
      {{ $visitors->links() }}
    @endif
    @endif
  </div>
</div>